const { exec } = require('child_process');
const fs = require('fs');

exec('npm install', (error, stdout, stderr) => {
    if (error) {
        console.error(`exec error: ${error}`);
        return;
    }

    const warnings = stderr.split('\n').filter(line => line.toLowerCase().includes('warning'));

    if (warnings.length > 0) {
        const logFilePath = 'npm_warnings.log';
        fs.writeFileSync(logFilePath, warnings.join('\n'));
        console.log(`npm warnings logged to ${C:xampp\htcdocs\backend}`);
    } else {
        console.log('No npm warnings found.');
    }
});
