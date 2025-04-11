const request = require('supertest');
const express = require('express');
const { adminFunctions } = require('../config/auth');
const apiRoutes = require('../routes/api');
const assert = require('assert');
const sinon = require('sinon');

const app = express();
app.use(express.json());

// Mock admin authentication
app.use((req, res, next) => {
    req.user = { id: 1, role: 'admin' };
    next();
});

app.use('/api', apiRoutes);

describe('Admin API', () => {
    let approveUserStub, denyUserStub, updateSettingsStub;

    beforeEach(() => {
        approveUserStub = sinon.stub(adminFunctions, 'approveUser').resolves();
        denyUserStub = sinon.stub(adminFunctions, 'denyUser').resolves();
        updateSettingsStub = sinon.stub(adminFunctions, 'updateMapSettings').resolves();
    });

    afterEach(() => {
        sinon.restore();
    });

    it('should approve pending user', async () => {
        const userId = 1;
        const res = await request(app)
            .post(`/api/admin/users/${userId}/approve`)
            .expect(200);

        assert.strictEqual(res.body.success, true);
        assert(approveUserStub.calledWith(userId));
    });

    it('should deny pending user', async () => {
        const userId = 1;
        const res = await request(app)
            .post(`/api/admin/users/${userId}/deny`)
            .expect(200);

        assert.strictEqual(res.body.success, true);
        assert(denyUserStub.calledWith(userId));
    });

    it('should update map settings', async () => {
        const settings = {
            defaultZoom: 14,
            defaultCenter: [53.8, -1.7]
        };

        const res = await request(app)
            .put('/api/settings/map')
            .send(settings)
            .expect(200);

        assert.strictEqual(res.body.success, true);
        assert(updateSettingsStub.calledWith(settings));
    });

    it('should handle errors properly', async () => {
        approveUserStub.rejects(new Error('Database error'));

        const res = await request(app)
            .post('/api/admin/users/1/approve')
            .expect(500);

        assert.strictEqual(res.body.error, 'Database error');
    });
});
