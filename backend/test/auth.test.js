const { passwordUtils, adminFunctions } = require('../config/auth');
const assert = require('assert');
const sinon = require('sinon');

describe('Password Utilities', () => {
    it('should hash a password', async () => {
        const hashedPassword = await passwordUtils.hashPassword('password123');
        assert.ok(hashedPassword);
        assert.notStrictEqual(hashedPassword, 'password123');
    });

    it('should compare a password with its hash', async () => {
        const hashedPassword = await passwordUtils.hashPassword('password123');
        const isMatch = await passwordUtils.comparePassword('password123', hashedPassword);
        assert.strictEqual(isMatch, true);
    });

    it('should not match an incorrect password', async () => {
        const hashedPassword = await passwordUtils.hashPassword('password123');
        const isMatch = await passwordUtils.comparePassword('wrongpassword', hashedPassword);
        assert.strictEqual(isMatch, false);
    });
});