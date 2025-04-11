import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';

const UserSettings = () => {
    const { user } = useAuth();
    const [settings, setSettings] = useState({
        mapDefaultView: user?.mapSettings || 'standard',
        notifications: user?.notifications || true,
        defaultFilters: user?.defaultFilters || []
    });

    const handleSettingsUpdate = async () => {
        try {
            const response = await fetch('http://localhost/backend/api/user/settings', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(settings)
            });
            if (response.ok) {
                alert('Settings updated successfully');
            }
        } catch (error) {
            console.error('Error updating settings:', error);
        }
    };

    return (
        <div className="user-settings">
            <h3>User Preferences</h3>
            <div className="settings-group">
                <label>
                    Map View
                    <select
                        value={settings.mapDefaultView}
                        onChange={(e) => setSettings({...settings, mapDefaultView: e.target.value})}
                    >
                        <option value="standard">Standard</option>
                        <option value="satellite">Satellite</option>
                        <option value="terrain">Terrain</option>
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={settings.notifications}
                        onChange={(e) => setSettings({...settings, notifications: e.target.checked})}
                    />
                    Enable Notifications
                </label>
            </div>
            <button onClick={handleSettingsUpdate}>Save Settings</button>
        </div>
    );
};

export default UserSettings;
