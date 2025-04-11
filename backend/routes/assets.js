const express = require('express');
const router = express.Router();
const { checkPermissions, db } = require('../config/auth');

router.post('/', checkPermissions('create_asset'), async (req, res) => {
    try {
        const { name, description, latitude, longitude, category } = req.body;
        const [result] = await db.promise().query(
            'INSERT INTO assets SET ?',
            { name, description, latitude, longitude, category, created_by: req.user.id }
        );
        res.json({ success: true, id: result.insertId });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.put('/:id', checkPermissions('edit_asset'), async (req, res) => {
    try {
        const { id } = req.params;
        const { name, description, category } = req.body;
        await db.promise().query(
            'UPDATE assets SET ? WHERE id = ? AND created_by = ?',
            [{ name, description, category }, id, req.user.id]
        );
        res.json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.delete('/:id', checkPermissions('delete_asset'), async (req, res) => {
    try {
        const { id } = req.params;
        await db.promise().query(
            'DELETE FROM assets WHERE id = ? AND created_by = ?',
            [id, req.user.id]
        );
        res.json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

module.exports = router;
