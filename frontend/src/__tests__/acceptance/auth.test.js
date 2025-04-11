import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { AuthProvider } from '../../contexts/AuthContext';
import LoginPage from '../../pages/LoginPage';
import { server } from '../../mocks/server';

describe('Authentication Flow', () => {
    beforeAll(() => server.listen());
    afterEach(() => {
        server.resetHandlers();
        localStorage.clear();
    });
    afterAll(() => server.close());

    it('should handle successful login', async () => {
        render(
            <MemoryRouter future={{ v7_startTransition: true }}>
                <AuthProvider>
                    <LoginPage />
                </AuthProvider>
            </MemoryRouter>
        );

        fireEvent.change(screen.getByLabelText(/email/i), {
            target: { value: 'admin@example.com' }
        });
        fireEvent.change(screen.getByLabelText(/password/i), {
            target: { value: 'admin123' }
        });

        fireEvent.click(screen.getByRole('button', { name: /sign in/i }));

        await waitFor(() => {
            expect(localStorage.getItem('authToken')).toBe('mock-admin-token');
            expect(localStorage.getItem('userRole')).toBe('admin');
        });
    });
});
