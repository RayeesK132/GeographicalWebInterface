const mysql = require('mysql2');
const bcrypt = require('bcrypt');
const nodemailer = require('nodemailer');
const jwt = require('jsonwebtoken');

// Database connection
const db = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '', // Default XAMPP MySQL password is often empty
    database: 'map_dashboard',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

db.getConnection((err, connection) => {
    if (err) {
        if (err.code === 'PROTOCOL_CONNECTION_LOST') {
            console.error('Database connection was closed.')
        }
        if (err.code === 'ER_CON_COUNT_ERROR') {
            console.error('Database has too many connections')
        }
        if (err.code === 'ECONNREFUSED') {
            console.error('Database connection was refused')
        }
    }
    if (connection) connection.release()
    return
})

// Email configuration
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: '{your-email}',
        pass: '{your-email-password}'
    }
});

const JWT_SECRET = 'your-secret-key'; // In production, use environment variable

const passwordUtils = {
    async hashPassword(password) {
        const salt = await bcrypt.genSalt(10);
        return bcrypt.hash(password, salt);
    },

    async comparePassword(password, hash) {
        return bcrypt.compare(password, hash);
    }
};

const adminFunctions = {
    async approveUser(userId) {
        const user = await db.promise().query('SELECT * FROM users WHERE id = ?', [userId]);
        if (user[0].length > 0) {
            await db.promise().query('UPDATE users SET status = ? WHERE id = ?', ['approved', userId]);
            
            // Send confirmation email
            const mailOptions = {
                from: '{your-email}',
                to: user[0][0].email,
                subject: 'Account Approved',
                text: 'Your account has been approved. You can now log in to the system.'
            };
            
            await transporter.sendMail(mailOptions);
        }
    },

    async updateUserPermissions(userId, permissions) {
        await db.promise().query('UPDATE users SET permissions = ? WHERE id = ?', [JSON.stringify(permissions), userId]);
    },

    async updateUserProfile(userId, profileData) {
        const allowedFields = ['phone', 'department'];
        const updates = {};
        allowedFields.forEach(field => {
            if (profileData[field]) {
                updates[field] = profileData[field];
            }
        });
    
        if (Object.keys(updates).length > 0) {
            await db.promise().query('UPDATE users SET ? WHERE id = ?', [updates, userId]);
        }
    },

    async denyUser(userId) {
        const user = await db.promise().query('SELECT * FROM users WHERE id = ?', [userId]);
        if (user[0].length > 0) {
            await db.promise().query('UPDATE users SET status = ? WHERE id = ?', ['denied', userId]);
            
            const mailOptions = {
                from: '{your-email}',
                to: user[0][0].email,
                subject: 'Account Access Denied',
                text: 'Your account access request has been denied.'
            };
            
            await transporter.sendMail(mailOptions);
        }
    },

    async getMapSettings() {
        const [settings] = await db.promise().query('SELECT * FROM map_settings');
        return settings[0] || {};
    },

    async updateMapSettings(settings) {
        await db.promise().query('UPDATE map_settings SET ? WHERE id = 1', [settings]);
    },

    async getUserPermissions(userId) {
        const [user] = await db.promise().query('SELECT permissions FROM users WHERE id = ?', [userId]);
        return user[0]?.permissions || {};
    },

    async resetUserPassword(userId, newPassword) {
        const hashedPassword = await passwordUtils.hashPassword(newPassword);
        await db.promise().query(
            'UPDATE users SET password = ? WHERE id = ?',
            [hashedPassword, userId]
        );
    },

    async changePassword(userId, oldPassword, newPassword) {
        const [user] = await db.promise().query(
            'SELECT password FROM users WHERE id = ?',
            [userId]
        );

        if (!user[0] || !(await passwordUtils.comparePassword(oldPassword, user[0].password))) {
            throw new Error('Invalid old password');
        }

        await this.resetUserPassword(userId, newPassword);
    },

    async getAssets() {
        const [assets] = await db.promise().query('SELECT * FROM assets');
        return assets;
    },

    async registerUser(userData) {
        const { email, password, fullName, phone, department } = userData;
        
        // Check if user already exists
        const [existingUser] = await db.promise().query('SELECT id FROM users WHERE email = ?', [email]);
        if (existingUser.length > 0) {
            throw new Error('Email already registered');
        }
        
        const hashedPassword = await passwordUtils.hashPassword(password);
        
        await db.promise().query(
            'INSERT INTO users (email, password, name, phone, department, status, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())',
            [email, hashedPassword, fullName, phone, department, 'pending', 'user']
        );
    },

    async loginUser(email, password) {
        const [users] = await db.promise().query('SELECT * FROM users WHERE email = ?', [email]);
        const user = users[0];
        
        if (!user) {
            throw new Error('Invalid credentials');
        }
        
        if (user.status !== 'approved') {
            throw new Error('Account is pending approval or has been denied');
        }
        
        const isValid = await passwordUtils.comparePassword(password, user.password);
        if (!isValid) {
            throw new Error('Invalid credentials');
        }
        
        const token = jwt.sign(
            { id: user.id, email: user.email, role: user.role },
            JWT_SECRET,
            { expiresIn: '24h' }
        );
        
        return token;
    }
};

module.exports = {
    adminFunctions,
    db,
    passwordUtils
};
