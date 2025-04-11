const { exec } = require('child_process');
const fs = require('fs');

// Install required testing dependencies first
exec('npm install --save-dev @testing-library/jest-dom@^5.16.5 @testing-library/react@^13.4.0 @testing-library/user-event@^14.4.3 msw@^1.2.1 react-scripts@5.0.1 isexe@^2.0.0 which@^2.0.2 --legacy-peer-deps --force', (error) => {
    if (error) {
        console.error(`Failed to install testing dependencies: ${error}`);
        return;
    }
    
    // Continue with npm install check
    exec('npm install', (error, stdout, stderr) => {
        if (error) {
            console.error(`exec error: ${error}`);
            return;
        }

        const warnings = stderr.split('\n').filter(line => line.toLowerCase().includes('warning'));

        if (warnings.length > 0) {
            const logFilePath = 'npm_warnings.log';
            fs.writeFileSync(logFilePath, warnings.join('\n'));
            console.log(`npm warnings logged to C:\\xampp\\htdocs\\backend`);
        } else {
            console.log('No npm warnings found.');
        }
    });
});
