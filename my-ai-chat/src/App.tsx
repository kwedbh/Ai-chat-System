// src/App.tsx
import React, { useState, useEffect } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Login from "./components/Auth/Login";
import Register from "./components/Auth/Register";
import Chat from "./components/Chat/Chat";
import LoadingSpinner from "./components/common/LoadingSpinner"; // Make sure to create this file
import './index.css';

const App: React.FC = () => {
    const [user, setUser] = useState<{ id: number; username: string } | null>(null);
    const [loading, setLoading] = useState(true); // New loading state
    const navigate = useNavigate();

    useEffect(() => {
        const checkAuth = async () => {
            try {
                // Fetch the PHP session check endpoint
                const response = await fetch("http://localhost/ai-chat-system/my-ai-chat/backend/check_session.php", {
                    credentials: "include",
                });
                const data = await response.json();
                
                // If a session exists, set the user state
                if (data.isAuthenticated) {
                    setUser(data.user);
                }
            } catch (error) {
                console.error("Session check failed:", error);
            } finally {
                setLoading(false); // Set loading to false regardless of the outcome
            }
        };

        checkAuth();
    }, []);

    const handleLogout = async () => {
        try {
            await fetch("http://localhost/ai-chat-system/my-ai-chat/backend/logout.php", {
                credentials: "include",
            });
            setUser(null);
            navigate("/login");
        } catch (error) {
            console.error("Logout failed:", error);
        }
    };
    
    // Show a loading spinner while checking the session
    if (loading) {
        return (
            <div className="w-full h-screen flex justify-center items-center bg-gray-100 dark:bg-gray-900">
                <LoadingSpinner />
            </div>
        );
    }

    return (
        <div className="w-full h-screen flex justify-center items-center bg-gray-100 dark:bg-gray-900">
            {user ? (
                // If logged in → Show Chat
                <Chat user={user} onLogout={handleLogout} />
            ) : (
                // If not logged in → Show Auth Routes
                <div className="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                    <Routes>
                        <Route path="/login" element={<Login onLogin={setUser} />} />
                        <Route path="/register" element={<Register />} />
                        {/* Default route → login */}
                        <Route path="*" element={<Login onLogin={setUser} />} />
                    </Routes>
                </div>
            )}
        </div>
    );
};

export default App;