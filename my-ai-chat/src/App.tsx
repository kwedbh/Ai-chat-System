// src/App.tsx
import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useNavigate } from 'react-router-dom';
import Login from './components/Auth/Login';
import Register from './components/Auth/Register';
import Chat from './components/Chat/Chat';
import './index.css';

const App: React.FC = () => {
    const [user, setUser] = useState<{ id: number; username: string } | null>(null);
    const navigate = useNavigate();

    useEffect(() => {
        // Check for an existing session (e.g., a token in local storage)
        // For simplicity, we'll check a session variable on the server side in PHP
        // You would typically use a JWT token here.
    }, []);

    const handleLogout = async () => {
        // Call a PHP logout endpoint to destroy the session
        await fetch('http://localhost/my-ai-chat/backend/logout.php');
        setUser(null);
        navigate('/login');
    };

    return (
        <div className="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            {user ? (
                <Chat user={user} onLogout={handleLogout} />
            ) : (
                <div className="w-full flex justify-center items-center">
                    <Routes>
                        <Route path="/login" element={<Login onLogin={setUser} />} />
                        <Route path="/register" element={<Register />} />
                        <Route path="*" element={<Login onLogin={setUser} />} />
                    </Routes>
                </div>
            )}
        </div>
    );
};

export default App;