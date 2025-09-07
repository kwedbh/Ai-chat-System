// src/App.tsx
import React, { useState, useEffect } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Login from "./components/Auth/Login";
import Register from "./components/Auth/Register";
import Chat from "./components/Chat/Chat";

const App: React.FC = () => {
  const [user, setUser] = useState<{ id: number; username: string } | null>(
    null
  );
  const navigate = useNavigate();

  useEffect(() => {
    // TODO: check if a user is already logged in (e.g., via localStorage or session)
    // Example: const savedUser = localStorage.getItem("user");
    // if (savedUser) setUser(JSON.parse(savedUser));
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

  return (
    <div className="w-full h-screen flex justify-center items-center bg-gray-100">
      {user ? (
        // If logged in → Show Chat
        <Chat user={user} onLogout={handleLogout} />
      ) : (
        // If not logged in → Show Auth Routes
        <div className="w-full max-w-md p-6 bg-white rounded-2xl shadow-lg">
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
