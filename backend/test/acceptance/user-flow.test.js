const request = require('supertest');
const app = require('../../server');
const { db } = require('../../config/auth');

describe('User Flow Acceptance Tests', () => {
    let authToken;

    beforeAll(async () => {
        // Clean test database
        await db.query('DELETE FROM users WHERE email = ?', ['test@example.com']);
    });

    it('should register a new user', async () => {
        const res = await request(app)
            .post('/api/auth/register')
            .send({
                email: 'test@example.com',
                password: 'password123',
                fullName: 'Test User',
                department: 'IT'
            });
        
        expect(res.statusCode).toBe(200);
        expect(res.body.success).toBe(true);
    });

    it('should login and get token', async () => {
        const res = await request(app)
            .post('/api/auth/login')
            .send({
                email: 'test@example.com',
                password: 'password123'
            });

        expect(res.statusCode).toBe(200);
        expect(res.body.token).toBeDefined();
        authToken = res.body.token;
    });

    it('should access protected routes with token', async () => {
        const res = await request(app)
            .get('/api/assets')
            .set('Authorization', `Bearer ${authToken}`);

        expect(res.statusCode).toBe(200);
        expect(Array.isArray(res.body)).toBe(true);
    });
});
