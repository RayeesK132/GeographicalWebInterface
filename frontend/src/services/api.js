import axios from 'axios';

const BASE_URL = 'http://localhost:80/api';

const axiosInstance = axios.create({
    baseURL: BASE_URL,
    withCredentials: true
});

axiosInstance.interceptors.request.use((config) => {
    const token = localStorage.getItem('authToken');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Mock API implementation
const mockApi = {
    login: async (email, password) => {
        const response = await fetch(`${BASE_URL}/auth/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
            credentials: 'include'
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Login failed');
        }
        
        return data;
    },
    getUsers: async () => {
        // Simulated API call
        return [
            { id: 1, email: 'user1@example.com', role: 'user' },
            { id: 2, email: 'user2@example.com', role: 'user' },
            { id: 3, email: 'admin@example.com', role: 'admin' }
        ];
    },

    getAssets: async () => {
        // Simulated API call
        return [
            { id: 1, name: 'Asset 1', type: 'image' },
            { id: 2, name: 'Asset 2', type: 'document' }
        ];
    },

    deleteUser: async (id) => {
        // Simulated API call
        return { success: true };
    },

    deleteAsset: async (id) => {
        // Simulated API call
        return { success: true };
    },

    updateUserRole: async (id, role) => {
        // Simulated API call
        return { success: true };
    }
};

export default process.env.NODE_ENV === 'test' ? mockApi : axiosInstance;
