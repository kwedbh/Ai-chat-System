import React, { useState, useEffect } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Login from "./components/Auth/Login";
import Register from "./components/Auth/Register";
import Chat from "./components/Chat/Chat";
import LoadingSpinner from "./components/common/LoadingSpinner";
import './index.css';
// CORRECT: The constant file is in the 'src' directory, so the path should be relative to it.
import { API_BASE_URL } from "./constants";

const App: React.FC = () => {
    const [user, setUser] = useState<{ id: number; username: string } | null>(null);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        const checkAuth = async () => {
            try {
                // CORRECT: Use the constant directly with the script name
                const response = await fetch(`${API_BASE_URL}/backend/check_session.php`, {
                    credentials: "include",
                });
                const data = await response.json();
                
                if (data.isAuthenticated) {
                    setUser(data.user);
                }
            } catch (error) {
                console.error("Session check failed:", error);
            } finally {
                setLoading(false);
            }
        };

        checkAuth();
    }, []);

    const handleLogout = async () => {
        try {
            // CORRECT: Use the constant directly with the script name
            await fetch(`${API_BASE_URL}/backend/logout.php`, {
                credentials: "include",
            });
            setUser(null);
            navigate("/login");
        } catch (error) {
            console.error("Logout failed:", error);
        }
    };
    
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
                <Chat user={user} onLogout={handleLogout} />
            ) : (
                <div className="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
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