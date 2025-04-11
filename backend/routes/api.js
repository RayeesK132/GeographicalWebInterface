const express = require('express');
const router = express.Router();
const { adminFunctions } = require('../config/auth');
const { db } = require('../config/auth');

router.post('/user/profile', async (req, res) => {
    try {
        await adminFunctions.updateUserProfile(req.user.id, req.body);
        res.json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.post('/auth/change-password', async (req, res) => {
    try {
        const { oldPassword, newPassword } = req.body;
        await adminFunctions.changePassword(req.user.id, oldPassword, newPassword);
        res.json({ success: true, message: 'Password updated successfully' });
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

router.post('/admin/reset-password/:userId', async (req, res) => {
    try {
        const { userId } = req.params;
        const { newPassword } = req.body;
        await adminFunctions.resetUserPassword(userId, newPassword);
        res.json({ success: true, message: 'Password reset successfully' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.post('/admin/users/:userId/:action', async (req, res) => {
    const { userId, action } = req.params;
    try {
        if (action === 'approve') {
            await adminFunctions.approveUser(userId);
        } else if (action === 'deny') {
            await adminFunctions.denyUser(userId);
        }
        res.json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.get('/assets', async (req, res) => {
    try {
        const [assets] = await db.promise().query('SELECT * FROM assets');
        res.json(assets);
    } catch (error) {
        console.error("Error fetching assets:", error);
        res.status(500).json({ error: error.message });
    }
});

router.post('/auth/register', async (req, res) => {
    try {
        const { email, password, fullName, phone, department } = req.body;
        await adminFunctions.registerUser({ email, password, fullName, phone, department });
        res.json({ success: true, message: 'Registration successful. Awaiting approval.' });
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

router.post('/auth/login', async (req, res) => {
    try {
        const { email, password } = req.body;
        const token = await adminFunctions.loginUser(email, password);
        res.json({ success: true, token });
    } catch (error) {
        res.status(401).json({ error: error.message });
    }
});

module.exports = router;
