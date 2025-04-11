import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { Navigate } from 'react-router-dom';
import api from '../services/api';

const AdminDashboard = () => {
    const { isAdmin } = useAuth();
    const [users, setUsers] = useState([]);
    const [assets, setAssets] = useState([]);
    const [pendingUsers, setPendingUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        const loadData = async () => {
            try {
                const [usersData, assetsData, pendingData] = await Promise.all([
                    api.getUsers(),
                    api.getAssets(),
                    api.getPendingUsers()
                ]);
                setUsers(usersData);
                setAssets(assetsData);
                setPendingUsers(pendingData);
            } catch (err) {
                setError('Failed to load data');
            } finally {
                setLoading(false);
            }
        };
        loadData();
    }, []);

    // Check admin status first
    if (!isAdmin()) {
        return <Navigate to="/login" replace />;
    }

    // Then loading state
    if (loading) {
        return <div>Loading...</div>;
    }

    // Then error state
    if (error) {
        return <div className="error">{error}</div>;
    }

    const handleDeleteUser = async (userId) => {
        try {
            await api.deleteUser(userId);
            setUsers(users.filter(user => user.id !== userId));
        } catch (err) {
            setError('Failed to delete user');
        }
    };

    const handleDeleteAsset = async (assetId) => {
        try {
            await api.deleteAsset(assetId);
            setAssets(assets.filter(asset => asset.id !== assetId));
        } catch (err) {
            setError('Failed to delete asset');
        }
    };

    const handleUpdatePermissions = async (userId, newRole) => {
        try {
            await api.updateUserPermissions(userId, newRole);
            setUsers(users.map(user => 
                user.id === userId ? { ...user, role: newRole } : user
            ));
        } catch (err) {
            setError('Failed to update permissions');
        }
    };

    const handleApproveUser = async (userId) => {
        try {
            await api.approveUser(userId);
            setPendingUsers(pendingUsers.filter(user => user.id !== userId));
        } catch (err) {
            setError('Failed to approve user');
        }
    };

    const handleDenyUser = async (userId) => {
        try {
            await api.denyUser(userId);
            setPendingUsers(pendingUsers.filter(user => user.id !== userId));
        } catch (err) {
            setError('Failed to deny user');
        }
    };

    return (
        <div className="admin-dashboard">
            <h1>Admin Dashboard</h1>
            
            <section className="pending-users">
                <h2>Pending Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {pendingUsers.map(user => (
                            <tr key={user.id}>
                                <td>{user.email}</td>
                                <td>Pending</td>
                                <td>
                                    <button onClick={() => handleApproveUser(user.id)}>
                                        Approve
                                    </button>
                                    <button onClick={() => handleDenyUser(user.id)}>
                                        Deny
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </section>

            <section className="user-management">
                <h2>User Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map(user => (
                            <tr key={user.id}>
                                <td>{user.email}</td>
                                <td>
                                    <select
                                        value={user.role}
                                        onChange={(e) => handleUpdatePermissions(user.id, e.target.value)}
                                    >
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </td>
                                <td>
                                    <button onClick={() => handleDeleteUser(user.id)}>Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </section>

            <section className="asset-management">
                <h2>Asset Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assets.map(asset => (
                            <tr key={asset.id}>
                                <td>{asset.name}</td>
                                <td>{asset.type}</td>
                                <td>
                                    <button onClick={() => handleDeleteAsset(asset.id)}>Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </section>
        </div>
    );
};

export default AdminDashboard;
