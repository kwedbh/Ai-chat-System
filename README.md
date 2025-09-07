# AI Chat System

A full-stack AI chat application built with React, PHP, and MySQL. This system allows users to register, log in, chat with an AI assistant, and save their conversation history.

## Features

-   **User Authentication**: Secure user registration and login.
-   **AI Chat**: Real-time conversational AI powered by Google's Gemini API.
-   **Conversation History**: Stores user prompts and AI replies in a database.
-   **Daily Prompt Limit**: Free users can send up to 25 prompts daily.
-   **Responsive UI**: A user interface styled with Tailwind CSS, similar to popular platforms like ChatGPT.

## Technologies

**Frontend (Client)**
-   **React**: A JavaScript library for building user interfaces.
-   **TypeScript**: A typed superset of JavaScript for enhanced development.
-   **Vite**: A fast development build tool.
-   **Tailwind CSS**: A utility-first CSS framework for rapid styling.
-   **React Router Dom**: For handling client-side routing.
-   **React Icons**: For a variety of popular icons.

**Backend (Server)**
-   **PHP**: A server-side scripting language for handling API requests.
-   **MySQL**: A relational database for storing user and chat data.
-   **Google Gemini API**: A free API for generating AI text responses.

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

-   **XAMPP**: To run the PHP backend and MySQL database.
-   **Node.js & npm**: To run the React frontend.
-   **Google Gemini API Key**: Obtain a key from [Google AI Studio](https://aistudio.google.com/app/apikey).

### Installation

1.  **Clone the Repository**
    ```sh
    git clone [https://github.com/kwedbh/Ai-chat-System](https://github.com/kwedbh/Ai-chat-System)
    cd my-ai-chat-app
    ```

2.  **Backend Setup**
    -   Place the `backend/` folder inside your XAMPP `htdocs` directory.
    -   Open phpMyAdmin (`http://localhost/phpmyadmin`) and create a new database named `ai_chat_db`.
    -   Run the SQL schema provided to create the necessary tables.
    -   Open `backend/db.php` and update the database credentials if necessary.
    -   In `backend/chat.php`, replace `"YOUR_GEMINI_API_KEY"` with your actual API key.

3.  **Frontend Setup**
    -   Navigate to the project root and install dependencies:
        ```sh
        npm install
        ```
    -   Start the development server:
        ```sh
        npm run dev
        ```

4.  **Access the Application**
    -   Open your browser and visit `http://localhost:5173`.

## Contributing

Contributions are welcome! If you find a bug or have an idea for a new feature, please open an issue or submit a pull request.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.