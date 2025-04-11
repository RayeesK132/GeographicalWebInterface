import React, { createContext, useState, useContext } from 'react';
import api from '../services/api';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [token, setToken] = useState(localStorage.getItem('authToken'));
    const [userRole, setUserRole] = useState(localStorage.getItem('userRole'));

    const login = async (email, password) => {
        try {
            const response = await api.login(email, password);
            localStorage.setItem('authToken', response.token);
            localStorage.setItem('userRole', response.role);
            setToken(response.token);
            setUserRole(response.role);
            return true;
        } catch (error) {
            console.error('Login error:', error);
            return false;
        }
    };

    const logout = () => {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userRole');
        setToken(null);
        setUserRole(null);
    };

    const isAdmin = () => userRole === 'admin';

    const value = {
        token,
        login,
        logout,
        isAdmin,
        userRole
    };

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};
