import api from '../services/api';
import { rest } from 'msw';
import { server } from '../mocks/server';

describe('API Integration', () => {
    it('should configure axios instance correctly', () => {
        expect(api.defaults.baseURL).toBe('http://localhost:80/api');
        expect(api.defaults.withCredentials).toBe(true);
    });

    it('should add auth token to requests', () => {
        localStorage.setItem('authToken', 'test-token');
        const config = api.interceptors.request.handlers[0].fulfilled({
            headers: {}
        });
        expect(config.headers.Authorization).toBe('Bearer test-token');
    });

    it('should handle API errors', async () => {
        server.use(
            rest.get('*/api/test', (req, res, ctx) => {
                return res(ctx.status(500));
            })
        );

        await expect(api.get('/test')).rejects.toThrow();
    });
});
