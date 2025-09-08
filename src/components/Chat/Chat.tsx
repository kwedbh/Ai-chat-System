// src/components/Chat/Chat.tsx
import React, { useState, useEffect, useRef, FormEvent } from 'react';
import { MdSend, MdOutlineClear } from 'react-icons/md';
import LoadingSpinner from '../common/LoadingSpinner';
import { API_BASE_URL } from '../../constants';

interface Message {
    id: number;
    sender: 'user' | 'ai';
    content: string;
}

interface Conversation {
    id: number;
    title: string;
}

interface ChatProps {
    user: { id: number; username: string };
    onLogout: () => void;
}

const Chat: React.FC<ChatProps> = ({ user, onLogout }) => {
    const [message, setMessage] = useState<string>('');
    const [chatHistory, setChatHistory] = useState<Message[]>([]);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [sessionId, setSessionId] = useState<number | null>(null);
    const [conversations, setConversations] = useState<Conversation[]>([]);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (user && user.id) {
            fetchConversations();
        }
    }, [user]);

    useEffect(() => {
        scrollToBottom();
    }, [chatHistory]);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    const fetchConversations = async () => {
        const response = await fetch(`${API_BASE_URL}/get_conversations.php`, {
            credentials: 'include'  
        });
        const data = await response.json();
        if (data.success) {
            setConversations(data.conversations);
        }
    };

    const fetchSession = async (id: number) => {
        setIsLoading(true);
        const response = await fetch(`${API_BASE_URL}/get_session.php?sessionId=${id}`, {
            credentials: 'include'  
        });
        const data = await response.json();
        if (data.success) {
            setChatHistory(data.messages);
            setSessionId(id);
        } else {
            setChatHistory([]);
            alert(data.message);
        }
        setIsLoading(false);
    };

    const handleSendMessage = async (e: FormEvent) => {
        e.preventDefault();
        if (!message.trim()) return;

        setIsLoading(true);
        const userMessage: Message = { id: Date.now(), sender: 'user', content: message };
        setChatHistory(prev => [...prev, userMessage]);
        setMessage('');

        try {
            const response = await fetch(`${API_BASE_URL}/chat.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ prompt: userMessage.content, sessionId }),
                credentials: 'include'  
            });

            const data = await response.json();
            if (data.success) {
                const aiMessage: Message = { id: Date.now() + 1, sender: 'ai', content: data.response };
                setChatHistory(prev => [...prev, aiMessage]);
                setSessionId(data.sessionId);
                fetchConversations();
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
        } finally {
            setIsLoading(false);
        }
    };

    const handleClearChat = async () => {
        if (!sessionId) {
            setChatHistory([]);
            return;
        }

        if (window.confirm('Are you sure you want to delete this conversation?')) {
            const response = await fetch(`${API_BASE_URL}/clear_session.php?sessionId=${sessionId}`, {
                credentials: 'include'  
            });
            const data = await response.json();
            if (data.success) {
                setChatHistory([]);
                setSessionId(null);
                fetchConversations();
            } else {
                alert(data.message);
            }
        }
    };

    return (
        <div className="flex flex-grow w-full h-full bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            {/* Sidebar */}
            <div className="w-1/4 p-4 border-r border-gray-300 dark:border-gray-700 overflow-y-auto">
                <div className="flex justify-between items-center mb-4">
                    <h3 className="text-xl font-bold">Conversations</h3>
                    <button onClick={() => { setChatHistory([]); setSessionId(null); }} className="p-2 rounded-lg text-sm bg-blue-500 text-white">
                        New Chat
                    </button>
                </div>
                <ul>
                    {conversations.map(conv => (
                        <li key={conv.id} onClick={() => fetchSession(conv.id)} className="p-2 hover:bg-gray-200 dark:hover:bg-gray-800 cursor-pointer rounded-lg truncate">
                            {conv.title || `Chat ${conv.id}`}
                        </li>
                    ))}
                </ul>
            </div>

            {/* Chat Window */}
            <div className="flex flex-col flex-grow">
                <header className="flex justify-between items-center p-4 border-b border-gray-300 dark:border-gray-700">
                    <h1 className="text-2xl font-bold">AI Chat</h1>
                    <div className="flex space-x-2">
                        <button onClick={handleClearChat} className="p-2 rounded-lg bg-red-500 text-white flex items-center">
                            <MdOutlineClear size={20} />
                            <span className="ml-1">Clear</span>
                        </button>
                        <button onClick={onLogout} className="px-4 py-2 rounded-lg bg-gray-500 text-white">Logout</button>
                    </div>
                </header>

                <div className="flex-grow p-4 overflow-y-auto space-y-4">
                    {chatHistory.map((msg, index) => (
                        <div key={index} className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                            <div className={`p-3 rounded-lg max-w-lg ${msg.sender === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100'}`}>
                                {msg.content}
                            </div>
                        </div>
                    ))}
                    {isLoading && <LoadingSpinner />}
                    <div ref={messagesEndRef} />
                </div>

                <form onSubmit={handleSendMessage} className="p-4 border-t border-gray-300 dark:border-gray-700 flex items-center">
                    <input
                        type="text"
                        value={message}
                        onChange={(e) => setMessage(e.target.value)}
                        placeholder="Type your message..."
                        className="flex-grow p-3 rounded-l-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none"
                    />
                    <button type="submit" className="p-3 bg-blue-500 text-white rounded-r-lg">
                        <MdSend size={24} />
                    </button>
                </form>
            </div>
        </div>
    );
};

export default Chat;