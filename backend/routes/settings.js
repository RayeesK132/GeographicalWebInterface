const express = require('express');
const router = express.Router();
const { checkPermissions, adminFunctions } = require('../config/auth');

router.get('/map', async (req, res) => {
    try {
        const settings = await adminFunctions.getMapSettings();
        res.json(settings);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

router.put('/map', checkPermissions('admin'), async (req, res) => {
    try {
        await adminFunctions.updateMapSettings(req.body);
        res.json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

module.exports = router;
