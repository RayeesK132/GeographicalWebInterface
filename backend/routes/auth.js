const express = require('express');
const router = express.Router();
const passport = require('passport');
const { BearerStrategy } = require('passport-azure-ad');

const config = {
    identityMetadata: 'https://login.microsoftonline.com/common/v2.0/.well-known/openid-configuration',
    clientID: process.env.AZURE_CLIENT_ID,
    validateIssuer: false,
    passReqToCallback: false,
    loggingLevel: 'info'
};

passport.use(new BearerStrategy(config,
    (token, done) => {
        done(null, token);
    })
));

router.get('/login', (req, res) => {
    res.redirect(`https://login.microsoftonline.com/common/oauth2/v2.0/authorize?
        client_id=${process.env.AZURE_CLIENT_ID}
        &response_type=code
        &redirect_uri=${encodeURIComponent(process.env.REDIRECT_URI)}
        &response_mode=query
        &scope=openid profile email
        &state=12345`);
});

router.get('/callback', async (req, res) => {
    const code = req.query.code;
    try {
        const tokenResponse = await fetch('https://login.microsoftonline.com/common/oauth2/v2.0/token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                grant_type: 'authorization_code',
                client_id: process.env.AZURE_CLIENT_ID,
                client_secret: process.env.AZURE_CLIENT_SECRET,
                code: code,
                redirect_uri: process.env.REDIRECT_URI,
                scope: 'openid profile email'
            })
        });

        const tokenData = await tokenResponse.json();
        if (tokenData.error) {
            throw new Error(tokenData.error_description);
        }

        // Get user info from token
        const userInfo = JSON.parse(Buffer.from(tokenData.id_token.split('.')[1], 'base64').toString());
        
        // Save user info to session
        req.session.user = {
            email: userInfo.email,
            name: userInfo.name,
            azureId: userInfo.oid
        };

        res.redirect('/');
    } catch (error) {
        console.error('Auth error:', error);
        res.status(500).send('Authentication failed');
    }
});

module.exports = router;
