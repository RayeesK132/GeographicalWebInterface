require('dotenv').config();
const express = require('express');
const cors = require('cors');
const morgan = require('morgan');
const passport = require('passport');
const session = require('express-session');
const path = require('path');
const { db } = require('./config/auth');
const apiRoutes = require('./routes/api');
const authRoutes = require('./routes/auth');
const localAuthRoutes = require('./routes/localAuth');

const app = express();
const PORT = process.env.PORT || 5000;

app.use(cors({
    origin: 'http://localhost:3000',  // React frontend URL
    credentials: true
}));

app.use(morgan('dev'));
app.use(express.json());
app.use(session({
    secret: process.env.SESSION_SECRET || 'your-secret-key',
    resave: false,
    saveUninitialized: false
}));

app.use(passport.initialize());
app.use(passport.session());

// Serve static files from public directory
app.use(express.static(path.join(__dirname, '../public')));

app.use('/api', apiRoutes);
app.use('/api/auth', authRoutes);
app.use('/api/auth/local', localAuthRoutes);

// Add error handling
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send('Something broke!');
});

app.get('/', (req, res) => {
  res.json({ message: 'API is running' });
});

app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
