const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const { db } = require('../config/auth');

router.post('/login', async (req, res) => {
    const { email, password } = req.body;
    try {
        const [users] = await db.promise().query('SELECT * FROM users WHERE email = ?', [email]);
        const user = users[0];

        if (!user) {
            return res.status(401).json({ error: 'User not found' });
        }

        const isValidPassword = await bcrypt.compare(password, user.password);
        if (!isValidPassword) {
            return res.status(401).json({ error: 'Invalid password' });
        }

        req.session.user = { id: user.id, email: user.email, role: user.role };
        res.json({ success: true, user: { id: user.id, email: user.email, role: user.role } });
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ error: 'Authentication failed' });
    }
});

router.post('/register', async (req, res) => {
    const { email, password, name } = req.body;
    try {
        const hashedPassword = await bcrypt.hash(password, 10);
        await db.promise().query(
            'INSERT INTO users (email, password, name, status) VALUES (?, ?, ?, ?)',
            [email, hashedPassword, name, 'pending']
        );
        res.json({ success: true, message: 'Registration successful. Please wait for admin approval.' });
    } catch (error) {
        console.error('Registration error:', error);
        res.status(500).json({ error: 'Registration failed' });
    }
});

module.exports = router;
