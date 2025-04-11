import React, { useState } from 'react';
import { bradfordColors } from '../theme';

const SignUp = ({ onSignUp }) => {
    const [formData, setFormData] = useState({
        email: '',
        password: '',
        confirmPassword: '',
        name: '',
        department: '',
        phone: ''
    });

    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (formData.password !== formData.confirmPassword) {
            setError('Passwords do not match');
            return;
        }

        try {
            const response = await fetch('http://localhost:5000/api/auth/local/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            if (data.success) {
                onSignUp(data.message);
            } else {
                setError(data.error);
            }
        } catch (error) {
            setError('Registration failed. Please try again.');
        }
    };

    return (
        <div className="auth-container">
            <div className="auth-header">
                <img src="/bradford-council-logo.png" alt="Bradford Council Logo" />
                <h2>Asset Tracker Registration</h2>
                <p>Please complete all required fields</p>
            </div>
            <form onSubmit={handleSubmit} className="auth-form">
                <div className="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        required
                        value={formData.email}
                        onChange={(e) => setFormData({...formData, email: e.target.value})}
                    />
                </div>
                <div className="form-group">
                    <label>Full Name</label>
                    <input
                        type="text"
                        required
                        value={formData.name}
                        onChange={(e) => setFormData({...formData, name: e.target.value})}
                    />
                </div>
                <div className="form-group">
                    <label>Department</label>
                    <select
                        value={formData.department}
                        onChange={(e) => setFormData({...formData, department: e.target.value})}
                        required
                    >
                        <option value="">Select Department</option>
                        <option value="IT">Information Technology</option>
                        <option value="Facilities">Facilities Management</option>
                        <option value="Operations">Operations</option>
                        <option value="Planning">Planning</option>
                        <option value="Housing">Housing</option>
                        <option value="Environment">Environment</option>
                    </select>
                </div>
                <div className="form-group">
                    <label>Phone</label>
                    <input
                        type="tel"
                        value={formData.phone}
                        onChange={(e) => setFormData({...formData, phone: e.target.value})}
                    />
                </div>
                <div className="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        required
                        value={formData.password}
                        onChange={(e) => setFormData({...formData, password: e.target.value})}
                    />
                </div>
                <div className="form-group">
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        required
                        value={formData.confirmPassword}
                        onChange={(e) => setFormData({...formData, confirmPassword: e.target.value})}
                    />
                </div>
                {error && <div className="error-message">{error}</div>}
                <button type="submit" className="btn-primary">Sign Up</button>
            </form>
        </div>
    );
};

export default SignUp;
