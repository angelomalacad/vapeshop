import { execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

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

// Writes a .env file from process.env IF .env does not already exist.
// On Hostinger, the panel "Environment Variables" are injected into the
// build container as process.env, so this reconstructs .env on each deploy.
// If .env already exists (e.g. locally, or from a previous deploy), it is
// left alone so manual edits are preserved.
function ensureEnvFile() {
    const envPath = path.join(process.cwd(), '.env');

    if (fs.existsSync(envPath)) {
        console.log('ENV: .env already exists — leaving it untouched.');
        return;
    }

    console.log('ENV: .env missing — generating from process.env...');

    const keys = [
        // App
        'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
        'APP_TIMEZONE', 'APP_LOCALE', 'APP_FALLBACK_LOCALE', 'APP_FAKER_LOCALE',
        'APP_MAINTENANCE_DRIVER',

        // Security
        'BCRYPT_ROUNDS',

        // Logging
        'LOG_CHANNEL', 'LOG_STACK', 'LOG_DEPRECATIONS_CHANNEL', 'LOG_LEVEL',

        // Database
        'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE',
        'DB_USERNAME', 'DB_PASSWORD',

        // Session
        'SESSION_DRIVER', 'SESSION_LIFETIME', 'SESSION_ENCRYPT',
        'SESSION_PATH', 'SESSION_DOMAIN',

        // Drivers
        'BROADCAST_CONNECTION', 'FILESYSTEM_DISK', 'QUEUE_CONNECTION',
        'CACHE_STORE',

        // Memcached / Redis
        'MEMCACHED_HOST', 'REDIS_CLIENT', 'REDIS_HOST',
        'REDIS_PASSWORD', 'REDIS_PORT',

        // Mail
        'MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME',
        'MAIL_PASSWORD', 'MAIL_ENCRYPTION', 'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME',

        // AWS
        'AWS_DEFAULT_REGION', 'AWS_USE_PATH_STYLE_ENDPOINT',

        // Pusher (only if you use them)
        'PUSHER_APP_ID', 'PUSHER_APP_KEY', 'PUSHER_APP_SECRET',
        'PUSHER_APP_CLUSTER',

        // Vite
        'VITE_APP_NAME',
    ];

    const lines = [];
    for (const key of keys) {
        const value = process.env[key];
        if (value === undefined) continue;

        // Quote if the value has spaces or shell-special chars.
        // Important: your DB_PASSWORD contains "^", which needs quoting.
        const needsQuoting = /[\s^$#!&*()<>|"'`\\]/.test(String(value));
        const escaped = String(value).replace(/\\/g, '\\\\').replace(/"/g, '\\"');
        const formatted = needsQuoting ? `"${escaped}"` : value;

        lines.push(`${key}=${formatted}`);
    }

    if (lines.length === 0) {
        console.log('ENV: WARN — no known env keys were available, skipping.');
        return;
    }

    fs.writeFileSync(envPath, lines.join('\n') + '\n', 'utf8');
    console.log(`ENV: wrote ${lines.length} variables to ${envPath}`);
}

console.log('=== Vape Expo deployment started ===');
console.log(`Platform: ${process.platform}`);
console.log(`PHP available: ${hasCommand('php')}`);
console.log(`Composer available: ${hasCommand('composer')}`);
console.log(`Node available: ${hasCommand('node')}`);

// Ensure .env exists BEFORE any artisan command runs.
ensureEnvFile();

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