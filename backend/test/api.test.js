const request = require('supertest');
const express = require('express');
const apiRoutes = require('../routes/api');
const { adminFunctions } = require('../config/auth');
const assert = require('assert');
const sinon = require('sinon');
const jwt = require('jsonwebtoken');

const app = express();
app.use(express.json());

// Add authentication middleware mock
app.use((req, res, next) => {
    req.user = { id: 1, role: 'admin' };
    next();
});

app.use('/', apiRoutes);

describe('API Routes', () => {
    let updateUserProfileStub, changePasswordStub, resetUserPasswordStub, approveUserStub, denyUserStub, getAssetsStub;

    beforeEach(() => {
        updateUserProfileStub = sinon.stub(adminFunctions, 'updateUserProfile').resolves();
        changePasswordStub = sinon.stub(adminFunctions, 'changePassword').resolves();
        resetUserPasswordStub = sinon.stub(adminFunctions, 'resetUserPassword').resolves();
        approveUserStub = sinon.stub(adminFunctions, 'approveUser').resolves();
        denyUserStub = sinon.stub(adminFunctions, 'denyUser').resolves();
        getAssetsStub = sinon.stub(adminFunctions, 'getAssets').resolves([{ id: 1, name: 'Asset 1' }]);
    });

    afterEach(() => {
        sinon.restore();
    });

    it('should update user profile', async () => {
        const res = await request(app)
            .post('/user/profile')
            .send({ phone: '123-456-7890', department: 'IT' });

        assert.strictEqual(res.statusCode, 200);
        assert.ok(updateUserProfileStub.called);
    });

    it('should change password', async () => {
        const res = await request(app)
            .post('/auth/change-password')
            .send({ oldPassword: 'oldpassword', newPassword: 'newpassword' });

        assert.strictEqual(res.statusCode, 200);
        assert.ok(changePasswordStub.called);
    });

    it('should reset user password', async () => {
        const userId = 1;
        const newPassword = 'newpassword';
        const res = await request(app)
            .post(`/admin/reset-password/${userId}`)
            .send({ newPassword: newPassword });

        assert.strictEqual(res.statusCode, 200);
        assert.ok(resetUserPasswordStub.called);
    });

    it('should approve a user', async () => {
        const userId = 1;
        const res = await request(app)
            .post(`/admin/users/${userId}/approve`);

        assert.strictEqual(res.statusCode, 200);
        assert.ok(approveUserStub.called);
    });

    it('should deny a user', async () => {
        const userId = 1;
        const res = await request(app)
            .post(`/admin/users/${userId}/deny`);

        assert.strictEqual(res.statusCode, 200);
        assert.ok(denyUserStub.called);
    });

    it('should get assets', async () => {
        const res = await request(app)
            .get('/assets');

        assert.strictEqual(res.statusCode, 200);
        assert.deepStrictEqual(res.body, [{ id: 1, name: 'Asset 1' }]);
    });

    describe('Error Handling', () => {
        it('should handle database errors', async () => {
            getAssetsStub.rejects(new Error('Database error'));
            const res = await request(app).get('/assets');
            assert.strictEqual(res.statusCode, 500);
            assert.ok(res.body.error);
        });

        it('should handle invalid input', async () => {
            const res = await request(app)
                .post('/auth/change-password')
                .send({ oldPassword: '' });
            assert.strictEqual(res.statusCode, 400);
        });
    });
});
