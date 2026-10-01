import { execSync } from 'node:child_process';

const isWindows = process.platform === 'win32';
const env = { ...process.env };
if (isWindows) {
    env.PATH = `C:\\Program Files\\nodejs;${env.PATH}`;
}

function run(command) {
    console.log(`\n> ${command}`);
    execSync(command, {
        stdio: 'inherit',
        shell: true,
        env,
    });
}

console.log('=== Vape Expo deployment started ===');

// Install PHP dependencies. Keep dev deps for Laravel Boost.
run('composer install --optimize-autoloader --no-scripts');

// Install JS dependencies.
run('npm install');

// Build frontend assets. Wayfinder regenerates resources/js/routes
// via the custom plugin in vite.config.ts, which also runs
// scripts/fix-wayfinder.mjs.
run('npm run build');

// .env is managed by Hostinger's panel — do not touch it here.

// Run production migrations.
run('php artisan migrate --force');

// Recreate storage symlink.
run('php artisan storage:link');

// Refresh Laravel caches.
// NOTE: route:cache is intentionally omitted — routes/web.php uses
// closure-based routes, and Laravel refuses to cache those.
run('php artisan optimize:clear');
run('php artisan config:cache');
run('php artisan view:cache');

console.log('=== Vape Expo deployment completed ===');