<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('writable_mode', 'chmod');
set('repository', 'https://github.com/prantobiswas51/astrology.git');
set('keep_releases', 1);


// Load environment variables
$hostname = getenv('DEPLOY_HOSTNAME');
$remoteUser = getenv('DEPLOY_REMOTE_USER');
$deployPath = getenv('DEPLOY_PATH');
$sshPort = getenv('DEPLOY_SSH_PORT');
$branch = getenv('DEPLOY_BRANCH') ?: 'main';

// Validate required environment variables
if (! $hostname) {
    throw new \RuntimeException('DEPLOY_HOSTNAME environment variable is required');
}
if (! $remoteUser) {
    throw new \RuntimeException('DEPLOY_REMOTE_USER environment variable is required');
}
if (! $deployPath) {
    throw new \RuntimeException('DEPLOY_PATH environment variable is required');
}

if (! $sshPort) {
    throw new \RuntimeException('DEPLOY_SSH_PORT environment variable is required');
}

// Hosts

host($hostname)
    ->set('remote_user', $remoteUser)
    ->set('deploy_path', $deployPath)
    ->set('http_user', 'www-data')
    ->set('port', $sshPort)
    ->set('branch', $branch);

// Tasks

// Build assets locally
task('build:assets', function () {
    writeln('Building assets locally...');
    runLocally('npm ci');
    runLocally('npm run build');
})->desc('Build assets locally');

// Upload built assets
task('upload:assets', function () {
    writeln('Uploading built assets...');
    $user = get('remote_user');
    $hostname = currentHost()->getHostname();
    $port = get('port');
    $releasePath = get('release_path');

    runLocally("scp -r -P {$port} public/build {$user}@{$hostname}:{$releasePath}/public/");
})->desc('Upload built assets to server');

// Skip npm tasks on server by overriding them
task('deploy:npm', function () {
    writeln('Skipping npm install on server (assets built locally)');
});

// Skip composer scripts on the server — proc_open is disabled on this
// cPanel host, and Composer needs it to run `post-autoload-dump` scripts
// (artisan package:discover / filament:upgrade). Run those manually below
// via plain SSH commands instead, which don't need proc_open.
set('composer_options', get('composer_options').' --no-scripts');

task('artisan:post-install', function () {
    run('cd {{release_path}} && {{bin/php}} artisan package:discover --ansi');
    run('cd {{release_path}} && {{bin/php}} artisan filament:upgrade');
})->desc('Run composer post-autoload-dump artisan commands manually');

// Hooks

// Build assets locally before deployment starts
before('deploy', 'build:assets');

// Upload assets after the release is prepared but before going live
after('deploy:vendors', 'upload:assets');

// Run the artisan commands composer's --no-scripts skipped
after('deploy:vendors', 'artisan:post-install');

after('deploy:failed', 'deploy:unlock');