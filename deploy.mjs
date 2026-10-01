import { execSync } from 'node:child_process';

function run(command) {
    console.log(`\n> ${command}`);
    execSync(command, {
        stdio: 'inherit',
        shell: true,
    });
}

console.log('=== Vape Expo deployment started ===');

// Keep dev dependencies because Laravel Boost is currently required
// when Laravel boots Artisan commands.
run('composer install --optimize-autoloader --no-scripts');

// Install frontend dependencies.
// Vite build is intentionally skipped.
run('npm install');

// Use the existing .env configured by Hostinger.
// Do NOT create, copy, or overwrite .env here.

// Run production database migrations.
run('php artisan migrate --force');

// Recreate Laravel's public/storage symlink.
run('php artisan storage:link');

console.log('=== Vape Expo deployment completed ===');

