import React from 'react';
import { render, screen, waitFor } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import { server } from './mocks/server';
import App from './App';

describe('App Component', () => {
    beforeAll(() => server.listen());
    afterEach(() => server.resetHandlers());
    afterAll(() => server.close());

    beforeEach(() => {
        localStorage.clear();
        jest.clearAllMocks();
    });

    it('renders the sign in form when user is not logged in', () => {
        render(
            <MemoryRouter 
                initialEntries={['/login']} 
                future={{ v7_startTransition: true, v7_relativeSplatPath: true }}
            >
                <App withRouter={false} />
            </MemoryRouter>
        );

        // Check for login form elements
        expect(screen.getByLabelText(/email/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/password/i)).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /sign in/i })).toBeInTheDocument();
    });

    it('redirects to admin dashboard when user is admin', async () => {
        localStorage.setItem('authToken', 'mock-admin-token');
        localStorage.setItem('userRole', 'admin');

        render(
            <MemoryRouter 
                initialEntries={['/admin']}
                future={{ v7_startTransition: true, v7_relativeSplatPath: true }}
            >
                <App withRouter={false} />
            </MemoryRouter>
        );

        // Wait for loading state to finish and data to load
        await waitFor(() => {
            expect(screen.queryByText(/loading/i)).not.toBeInTheDocument();
            expect(screen.queryByText(/failed to load data/i)).not.toBeInTheDocument();
        });

        // Then check for dashboard content
        expect(screen.getByText(/admin dashboard/i)).toBeInTheDocument();
    });
});
