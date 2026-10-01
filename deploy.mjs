import { execSync } from 'node:child_process';
import fs from 'node:fs';

const isWindows = process.platform === 'win32';
const env = { ...process.env };

if (isWindows) {
    // Ensure Node and Composer are on PATH for child processes.
    const extras = [
        'C:\\Program Files\\nodejs',
        'C:\\ProgramData\\ComposerSetup\\bin',
        'C:\\xampp\\php',
    ].filter((p) => fs.existsSync(p));

    env.PATH = extras.join(';') + ';' + env.PATH;
}

function hasCommand(cmd) {
    try {
        execSync(`${cmd} --version`, {
            stdio: 'ignore',
            shell: true,
            env,
        });
        return true;
    } catch {
        return false;
    }
}

function run(command, { optional = false } = {}) {
    console.log(`\n> ${command}`);
    try {
        execSync(command, {
            stdio: 'inherit',
            shell: true,
            env,
        });
    } catch (err) {
        if (optional) {
            console.log(`WARN: optional step failed: ${command}`);
            return;
        }
        console.error(`FAILED: ${command}`);
        process.exit(1);
    }
}

console.log('=== Vape Expo deployment started ===');
console.log(`Platform: ${process.platform}`);
console.log(`PHP available: ${hasCommand('php')}`);
console.log(`Composer available: ${hasCommand('composer')}`);
console.log(`Node available: ${hasCommand('node')}`);

if (hasCommand('composer')) {
    run('composer install --optimize-autoloader --no-scripts');
} else {
    console.log('WARN: composer not found, skipping composer install.');
}

run('npm install');

run('npm run build:vite');

if (hasCommand('php')) {
    run('php artisan migrate --force', { optional: true });
    run('php artisan storage:link || true', { optional: true });
    run('php artisan optimize:clear', { optional: true });
    run('php artisan view:cache', { optional: true });
} else {
    console.log('WARN: php not found, skipping Laravel post-deploy.');
}

console.log('=== Vape Expo deployment completed ===');