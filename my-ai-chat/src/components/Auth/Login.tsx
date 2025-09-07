// src/components/Auth/Login.tsx
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

interface LoginProps {
    onLogin: (user: { id: number; username: string }) => void;
}

const Login: React.FC<LoginProps> = ({ onLogin }) => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setMessage('');
        try {
            const response = await fetch('http://localhost/ai-chat-system/my-ai-chat/backend/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            const data = await response.json();
            if (data.success) {
                onLogin(data.user);
                navigate('/');
            } else {
                setMessage(data.message);
            }
        } catch (error) {
            setMessage('Login failed. Please try again.');
        }
    };

    return (
        <form onSubmit={handleSubmit} className="w-full max-w-sm p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <h2 className="text-2xl font-bold mb-6 text-center">Login to AI Chat</h2>
            <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full p-3 mb-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                required
            />
            <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className="w-full p-3 mb-6 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                required
            />
            <button
                type="submit"
                className="w-full p-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-600 transition-colors"
            >
                Login
            </button>
            {message && <p className="text-red-500 text-center mt-4">{message}</p>}
            <p className="text-center mt-4">
                Don't have an account? <a href="/register" className="text-blue-500 hover:underline">Register</a>
            </p>
        </form>
    );
};

export default Login;