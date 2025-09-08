<?php

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
$session_id = $data->session_id ?? null;

try {
    // 1. Check daily prompt limit for free users
    if ($_SESSION['plan'] === 'free') {
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

    // 4. Call the AI API (using cURL for a simple example)
    $gemini_api_key = "YOUR_GEMINI_API_KEY"; // Get this from Google AI Studio
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$gemini_api_key";
    $payload = json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ai_response = "An error occurred.";
    if ($http_code == 200) {
        $result = json_decode($response, true);
        $ai_response = $result['candidates'][0]['content']['parts'][0]['text'] ?? "No response.";
    }

    // 5. Save AI's reply
    $stmt = $conn->prepare("INSERT INTO messages (session_id, sender, content) VALUES (?, 'ai', ?)");
    $stmt->execute([$session_id, $ai_response]);

    // 6. Return the response to the frontend
    echo json_encode(['success' => true, 'response' => $ai_response, 'sessionId' => $session_id]);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>