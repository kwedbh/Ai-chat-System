<?php
include 'session.php'; 
include 'cors.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->prompt)) {
    echo json_encode(['success' => false, 'message' => 'Prompt is required.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$prompt = $data->prompt;
$session_id = $data->sessionId ?? null; // Fixed: should match frontend (sessionId not session_id)

try {
    // 1. Check daily prompt limit for free users
    if (isset($_SESSION['plan']) && $_SESSION['plan'] === 'free') {
        $stmt = $conn->prepare("SELECT daily_prompt_count, last_prompt_date FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Reset daily count if date is not today
        $current_date = date("Y-m-d");
        if ($user['last_prompt_date'] !== $current_date) {
            $stmt = $conn->prepare("UPDATE users SET daily_prompt_count = 0, last_prompt_date = ? WHERE id = ?");
            $stmt->execute([$current_date, $user_id]);
            $user['daily_prompt_count'] = 0;
        }

        if ($user['daily_prompt_count'] >= 25) {
            echo json_encode(['success' => false, 'message' => 'Daily prompt limit reached. Please upgrade or try again tomorrow.']);
            exit;
        }

        // Increment prompt count
        $stmt = $conn->prepare("UPDATE users SET daily_prompt_count = daily_prompt_count + 1 WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    // 2. Create or get chat session
    if (!$session_id) {
        $stmt = $conn->prepare("INSERT INTO chat_sessions (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $session_id = $conn->lastInsertId();
    }

    // 3. Save user's prompt
    $stmt = $conn->prepare("INSERT INTO messages (session_id, sender, content) VALUES (?, 'user', ?)");
    $stmt->execute([$session_id, $prompt]);

    // 4. Call Gemini API
    $ai_response = callGeminiAPI($prompt);

    // 5. Save AI's reply
    $stmt = $conn->prepare("INSERT INTO messages (session_id, sender, content) VALUES (?, 'ai', ?)");
    $stmt->execute([$session_id, $ai_response]);

    // 6. Update session title if this is the first exchange
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM messages WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $message_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($message_count <= 2) { // First user message and first AI response
        $title = strlen($prompt) > 50 ? substr($prompt, 0, 50) . '...' : $prompt;
        $stmt = $conn->prepare("UPDATE chat_sessions SET title = ? WHERE id = ?");
        $stmt->execute([$title, $session_id]);
    }

    // 7. Return the response to the frontend
    echo json_encode(['success' => true, 'response' => $ai_response, 'sessionId' => $session_id]);

} catch(Exception $e) {
    error_log("Chat error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request.']);
}

function callGeminiAPI($prompt) {
    // Use the API key from the separate file
    $gemini_api_key = GEMINI_API_KEY;
    
    if ($gemini_api_key === 'AIzaSyC-PUT_YOUR_ACTUAL_API_KEY_HERE' || empty($gemini_api_key)) {
        error_log("Gemini API key not set!");
        return "Please configure the Gemini API key in the backend to use the AI chat feature.";
    }
    
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $gemini_api_key;
    
    $payload = json_encode([
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.9,
            'topK' => 1,
            'topP' => 1,
            'maxOutputTokens' => 2048,
        ],
        'safetySettings' => [
            [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ]
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Handle cURL errors
    if ($curl_error) {
        error_log("CURL Error: " . $curl_error);
        return "Sorry, I'm having trouble connecting to the AI service. Please try again later.";
    }

    // Handle HTTP errors
    if ($http_code !== 200) {
        error_log("Gemini API Error - HTTP Code: " . $http_code . ", Response: " . $response);
        
        if ($http_code === 400) {
            return "Invalid request. Please check your message and try again.";
        } elseif ($http_code === 401) {
            return "API authentication failed. Please check the API key configuration.";
        } elseif ($http_code === 429) {
            return "Rate limit exceeded. Please wait a moment and try again.";
        } else {
            return "Sorry, the AI service is temporarily unavailable. Please try again later.";
        }
    }

    // Parse the response
    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        return "Sorry, there was an error processing the AI response.";
    }
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return $result['candidates'][0]['content']['parts'][0]['text'];
    } elseif (isset($result['error'])) {
        error_log("Gemini API Error: " . json_encode($result['error']));
        return "The AI service returned an error. Please try again with a different message.";
    } else {
        error_log("Gemini API - Unexpected response format: " . $response);
        return "I apologize, but I couldn't generate a proper response. Please try rephrasing your question.";
    }
}
