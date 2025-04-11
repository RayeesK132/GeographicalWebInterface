module.exports = {
    testEnvironment: 'jsdom',
    setupFilesAfterEnv: ['<rootDir>/src/setupTests.js'],
    moduleNameMapper: {
        '\\.(css|less|scss|sass)$': 'identity-obj-proxy'
    },
    transform: {
        '^.+\\.(js|jsx)$': ['babel-jest', { rootMode: 'upward' }]
    },
    transformIgnorePatterns: [
        'node_modules/(?!(axios)/)'
    ]
};
