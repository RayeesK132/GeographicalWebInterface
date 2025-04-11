import { rest } from 'msw';

const BASE_URL = 'http://localhost';

const mockUsers = [
    { id: 1, email: 'user1@example.com', role: 'user' },
    { id: 2, email: 'user2@example.com', role: 'user' },
    { id: 3, email: 'admin@example.com', role: 'admin' }
];

const mockAssets = [
    { id: 1, name: 'Asset 1', type: 'image' },
    { id: 2, name: 'Asset 2', type: 'document' }
];

const mockPendingUsers = [
    { id: 4, email: 'pending@example.com', status: 'pending' }
];

export const handlers = [
    rest.post(`${BASE_URL}/api/auth/login`, async (req, res, ctx) => {
        const { email, password } = await req.json();
        
        if (email === 'admin@example.com' && password === 'admin123') {
            return res(
                ctx.status(200),
                ctx.json({
                    success: true,
                    token: 'mock-admin-token',
                    role: 'admin'
                })
            );
        }
        
        return res(
            ctx.status(401),
            ctx.json({ 
                success: false,
                message: 'Invalid credentials' 
            })
        );
    }),

    rest.get(`${BASE_URL}/api/users`, (req, res, ctx) => {
        return res(ctx.json(mockUsers));
    }),

    rest.get(`${BASE_URL}/api/assets`, (req, res, ctx) => {
        return res(ctx.json(mockAssets));
    }),

    rest.get(`${BASE_URL}/api/admin/pending-users`, (req, res, ctx) => {
        return res(ctx.json(mockPendingUsers));
    }),

    rest.post(`${BASE_URL}/api/admin/users/:userId/approve`, (req, res, ctx) => {
        const { userId } = req.params;
        mockPendingUsers.splice(mockPendingUsers.findIndex(u => u.id === parseInt(userId)), 1);
        return res(ctx.json({ success: true }));
    }),

    rest.post(`${BASE_URL}/api/admin/users/:userId/deny`, (req, res, ctx) => {
        const { userId } = req.params;
        mockPendingUsers.splice(mockPendingUsers.findIndex(u => u.id === parseInt(userId)), 1);
        return res(ctx.json({ success: true }));
    }),

    rest.put(`${BASE_URL}/api/settings/map`, async (req, res, ctx) => {
        const settings = await req.json();
        return res(ctx.json({ success: true, settings }));
    })
];
