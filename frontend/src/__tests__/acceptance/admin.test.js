import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { server } from '../../mocks/server';
import App from '../../App';

describe('Admin Flow', () => {
    beforeAll(() => server.listen());
    afterEach(() => {
        server.resetHandlers();
        localStorage.clear();
    });
    afterAll(() => server.close());

    it('should show admin dashboard after successful login', async () => {
        render(
            <MemoryRouter initialEntries={['/login']}>
                <App withRouter={false} />
            </MemoryRouter>
        );

        // Login as admin
        fireEvent.change(screen.getByLabelText(/email/i), {
            target: { value: 'admin@example.com' }
        });
        fireEvent.change(screen.getByLabelText(/password/i), {
            target: { value: 'admin123' }
        });
        fireEvent.click(screen.getByRole('button', { name: /sign in/i }));

        // Wait for dashboard to load
        await waitFor(() => {
            expect(screen.getByText('User Management')).toBeInTheDocument();
        });

        // Check if user list is loaded
        expect(screen.getByText('user1@example.com')).toBeInTheDocument();
        expect(screen.getByText('user2@example.com')).toBeInTheDocument();
    });

    it('should handle user approval', async () => {
        localStorage.setItem('authToken', 'mock-admin-token');
        localStorage.setItem('userRole', 'admin');

        render(
            <MemoryRouter initialEntries={['/admin']}>
                <App withRouter={false} />
            </MemoryRouter>
        );

        // Wait for pending users section to load
        await waitFor(() => {
            expect(screen.getByText('Pending Users')).toBeInTheDocument();
        });

        expect(screen.getByText('pending@example.com')).toBeInTheDocument();
        
        const approveButton = screen.getByRole('button', { name: /approve/i });
        fireEvent.click(approveButton);

        await waitFor(() => {
            expect(screen.queryByText('pending@example.com')).not.toBeInTheDocument();
        });
    });
});
