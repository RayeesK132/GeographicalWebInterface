import React from 'react';

const UserProfileForm = ({ profileData, setProfileData, onSubmit }) => {
    return (
        <form onSubmit={onSubmit} className="profile-form">
            <h2>Complete Your Profile</h2>
            <input
                type="tel"
                value={profileData.phone}
                onChange={(e) => setProfileData({...profileData, phone: e.target.value})}
                placeholder="Phone Number"
                required
            />
            <input
                type="text"
                value={profileData.department}
                onChange={(e) => setProfileData({...profileData, department: e.target.value})}
                placeholder="Department"
                required
            />
            <select
                value={profileData.role}
                onChange={(e) => setProfileData({...profileData, role: e.target.value})}
                required
            >
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Save Profile</button>
        </form>
    );
};

export default UserProfileForm;
