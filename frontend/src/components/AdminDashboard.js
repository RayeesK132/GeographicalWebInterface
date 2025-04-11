import React, { useState, useEffect } from 'react';

const AdminDashboard = () => {
    const [pendingUsers, setPendingUsers] = useState([]);
    const [mapSettings, setMapSettings] = useState({
        defaultZoom: 13,
        defaultCenter: [53.795, -1.759],
        allowedFeatures: {
            addAssets: true,
            deleteAssets: true,
            editAssets: true,
            filters: true
        }
    });

    const handleUserApproval = async (userId, action) => {
        const response = await fetch(`http://localhost/backend/api/admin/users/${userId}/${action}`, {
            method: 'POST'
        });
        if (response.ok) {
            setPendingUsers(pendingUsers.filter(user => user.id !== userId));
        }
    };

    const handleMapSettingsUpdate = async () => {
        const response = await fetch('http://localhost/backend/api/settings/map', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(mapSettings)
        });
        if (response.ok) {
            alert('Map settings updated successfully');
        }
    };

    return (
        <div className="admin-dashboard">
            <h2>Admin Dashboard</h2>
            <div className="pending-users">
                {pendingUsers.map(user => (
                    <div key={user.id} className="user-card">
                        <p>{user.name} ({user.email})</p>
                        <p>Department: {user.department}</p>
                        <button onClick={() => handleUserApproval(user.id, 'approve')}>Approve</button>
                        <button onClick={() => handleUserApproval(user.id, 'deny')}>Deny</button>
                    </div>
                ))}
            </div>
            <div className="map-settings">
                <h3>Map Settings</h3>
                <div className="settings-form">
                    <label>
                        Default Zoom:
                        <input
                            type="number"
                            value={mapSettings.defaultZoom}
                            onChange={(e) => setMapSettings({
                                ...mapSettings,
                                defaultZoom: parseInt(e.target.value)
                            })}
                        />
                    </label>
                    {/* Add more settings controls */}
                    <button onClick={handleMapSettingsUpdate}>
                        Update Settings
                    </button>
                </div>
            </div>
        </div>
    );
};

export default AdminDashboard;
