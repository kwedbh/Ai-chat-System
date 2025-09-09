<div align="center">

# 🤖✨ AI Chat System ✨🤖

<img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&size=18&duration=4000&pause=1000&color=36BCF7&center=true&vCenter=true&width=600&lines=A+full-stack+AI+chat+application;Built+with+React%2C+PHP%2C+and+MySQL;Real-time+conversational+AI;Powered+by+Google's+Gemini+API" alt="Typing SVG" />

[![Stars](https://img.shields.io/github/stars/kwedbh/ai-chat-system?style=for-the-badge&logo=github&color=gold)](https://github.com/kwedbh/ai-chat-system)
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)
[![React](https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)](https://reactjs.org/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)

</div>

---

<div align="center">

### 🎯 A full-stack AI chat application built with React, PHP, and MySQL. This system allows users to register, log in, chat with an AI assistant, and save their conversation history.

</div>

---

## 🚀 Features

<table>
<tr>
<td>

🔐 **User Authentication**: Secure user registration and login.

</td>
<td>

🤖 **AI Chat**: Real-time conversational AI powered by Google's Gemini API.

</td>
</tr>
<tr>
<td>

💾 **Conversation History**: Stores user prompts and AI replies in a database.

</td>
<td>

⏰ **Daily Prompt Limit**: Free users can send up to 25 prompts daily.

</td>
</tr>
<tr>
<td colspan="2">

📱 **Responsive UI**: A user-friendly interface styled with Tailwind CSS, similar to popular platforms like ChatGPT.

</td>
</tr>
</table>

---

## 🛠️ Technologies

<div align="center">

### 🎨 Frontend (Client)

</div>

| Technology | Description | Badge |
|------------|-------------|-------|
| **React** | A JavaScript library for building user interfaces | ![React](https://img.shields.io/badge/React-20232A?style=flat-square&logo=react&logoColor=61DAFB) |
| **TypeScript** | A typed superset of JavaScript for enhanced development | ![TypeScript](https://img.shields.io/badge/TypeScript-007ACC?style=flat-square&logo=typescript&logoColor=white) |
| **Vite** | A fast development build tool | ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white) |
| **Tailwind CSS** | A utility-first CSS framework for rapid styling | ![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white) |
| **React Router Dom** | For handling client-side routing | ![React Router](https://img.shields.io/badge/React_Router-CA4245?style=flat-square&logo=react-router&logoColor=white) |
| **React Icons** | For a variety of popular icons | ![React](https://img.shields.io/badge/React_Icons-61DAFB?style=flat-square&logo=react&logoColor=black) |

<div align="center">

### ⚙️ Backend (Server)

</div>

| Technology | Description | Badge |
|------------|-------------|-------|
| **PHP** | A server-side scripting language for handling API requests | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) |
| **MySQL** | A relational database for storing user and chat data | ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat-square&logo=mysql&logoColor=white) |
| **Google Gemini API** | A free API for generating AI text responses | ![Google](https://img.shields.io/badge/Google_Gemini-4285F4?style=flat-square&logo=google&logoColor=white) |

---

<div align="center">

## 🚦 Getting Started

### 📋 Follow these steps to set up and run the project locally.

</div>

### 📚 Prerequisites

<details>
<summary>Click to expand prerequisites</summary>

- **XAMPP**: To run the PHP backend and MySQL database.
- **Node.js & npm**: To run the React frontend.
- **Google Gemini API Key**: Obtain a key from [Google AI Studio](https://aistudio.google.com/app/apikey).

</details>

---

### 💻 Installation

<div align="center">

#### Step 1️⃣: Clone the Repository

</div>

```bash
git clone https://github.com/kwedbh/ai-chat-system YourApp
cd YourApp
```

<div align="center">

#### Step 2️⃣: Backend Setup

</div>

- Place the `backend/` folder inside your XAMPP `htdocs` directory.
- Open phpMyAdmin (`http://localhost/phpmyadmin`) and create a new database named `ai_chat_db`.
- Run the SQL schema provided to create the necessary tables.
- Open `backend/db.php` and update the database credentials if necessary.
- In `backend/chat.php`, replace `"YOUR_GEMINI_API_KEY"` with your actual API key.

<div align="center">

#### Step 3️⃣: Frontend Setup

</div>

- Navigate to the project root and install dependencies:
    ```sh
    npm install
    ```
- Start the development server:
    ```sh
    npm run dev
    ```

<div align="center">

#### Step 4️⃣: Access the Application

</div>

- Open your browser and visit `http://localhost:5173`.

---

<div align="center">

## 🤝 Contributing

### 🎉 Contributions are welcome! If you find a bug or have an idea for a new feature, please open an issue or submit a pull request.

[![Contributors](https://contrib.rocks/image?repo=kwedbh/ai-chat-system)](https://github.com/kwedbh/ai-chat-system/graphs/contributors)

</div>

---

<div align="center">

## 📜 License

### This project is licensed under the MIT License.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

---

<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&height=100&section=footer" width="100%" />

**Made with ❤️ and lots of ☕**

</div>
