import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import LoginPage from './pages/LoginPage';
import AdminDashboard from './pages/AdminDashboard';

const App = ({ withRouter = true }) => {
    const AppContent = () => (
        <AuthProvider>
            <Routes>
                <Route path="/login" element={<LoginPage />} />
                <Route path="/admin" element={<AdminDashboard />} />
                <Route path="/" element={<Navigate to="/login" replace />} />
            </Routes>
        </AuthProvider>
    );

    if (!withRouter) {
        return <AppContent />;
    }

    return (
        <BrowserRouter future={{ v7_startTransition: true }}>
            <AppContent />
        </BrowserRouter>
    );
};

export default App;