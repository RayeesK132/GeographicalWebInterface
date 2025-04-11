# Admin Dashboard Application

A full-stack application with admin dashboard functionality, user management, and authentication.

## Project Structure

```
frontend/                  # React frontend application
├── src/
│   ├── components/       # React components
│   ├── contexts/        # React contexts (Auth)
│   ├── pages/           # Page components
│   ├── services/        # API services
│   ├── mocks/           # MSW mock handlers
│   └── __tests__/       # Test files
│
backend/                  # Express.js backend application
├── config/              # Configuration files
├── routes/              # API routes
└── test/                # Backend tests

```

## Features

- User Authentication (Local & Microsoft Azure)
- Admin Dashboard
- User Management
  - Approve/Deny pending users
  - Update user roles
  - Delete users
- Asset Management
- Map Settings Configuration
- Comprehensive Testing Suite

## Setup Instructions

### Prerequisites
- Node.js (v14+)
- XAMPP (for MySQL database)
- npm or yarn

### Backend Setup

1. Start XAMPP and ensure MySQL is running
2. Navigate to backend directory:
```bash
cd backend
npm install
```

3. Create `.env` file:
```env
PORT=5000
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=map_dashboard
SESSION_SECRET=your-secret-key
AZURE_CLIENT_ID=your-azure-client-id
AZURE_CLIENT_SECRET=your-azure-client-secret
```

4. Initialize database:
```bash
npm run db:init
```

5. Start backend server:
```bash
npm start
```

### Frontend Setup

1. Navigate to frontend directory:
```bash
cd frontend
npm install
```

2. Start development server:
```bash
npm start
```

## Testing

### Frontend Tests

```bash
cd frontend
npm test
```

This runs:
- Unit tests for components
- Integration tests for API services
- End-to-end tests for user flows

### Backend Tests

```bash
cd backend
npm test
```

## API Endpoints

### Authentication
- POST `/api/auth/login` - Local login
- GET `/api/auth/login` - Microsoft login
- GET `/api/auth/callback` - OAuth callback

### Admin
- GET `/api/users` - Get all users
- POST `/api/admin/users/:userId/approve` - Approve user
- POST `/api/admin/users/:userId/deny` - Deny user
- PUT `/api/settings/map` - Update map settings

### Assets
- GET `/api/assets` - Get all assets
- POST `/api/assets` - Create asset
- DELETE `/api/assets/:id` - Delete asset

## Code Overview

### Frontend

1. **Authentication Context (`AuthContext.js`)**
   - Manages user authentication state
   - Provides login/logout functionality
   - Stores auth token and user role

2. **Admin Dashboard (`AdminDashboard.js`)**
   - Displays user management interface
   - Handles user approval/denial
   - Manages assets and settings

3. **API Service (`api.js`)**
   - Handles API communication
   - Includes mock implementations for testing
   - Uses axios for production requests

### Backend

1. **Authentication Routes (`auth.js`)**
   - Handles local and Microsoft authentication
   - Manages user sessions
   - Validates tokens

2. **Admin Functions (`config/auth.js`)**
   - User management logic
   - Password hashing
   - Email notifications

3. **API Routes (`api.js`)**
   - RESTful endpoints
   - Protected routes
   - Error handling

## Testing Strategy

1. **Frontend Tests**
   - Mock Service Worker for API mocking
   - React Testing Library for component testing
   - Integration tests for user flows

2. **Backend Tests**
   - Supertest for API testing
   - Sinon for mocking
   - Unit tests for utilities

## Contributing

1. Fork the repository
2. Create your feature branch
3. Write tests for new features
4. Submit a pull request

## License

MIT License
