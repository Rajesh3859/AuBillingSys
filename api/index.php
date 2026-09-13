<?php

// Forward static assets if requested
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Ensure Laravel storage and bootstrap cache directories exist in Vercel writable /tmp
$storageDir = '/tmp/storage';
if (!file_exists($storageDir)) {
    @mkdir($storageDir . '/app/public', 0755, true);
    @mkdir($storageDir . '/framework/views', 0755, true);
    @mkdir($storageDir . '/framework/cache/data', 0755, true);
    @mkdir($storageDir . '/framework/sessions', 0755, true);
    @mkdir($storageDir . '/logs', 0755, true);
    @mkdir('/tmp/bootstrap/cache', 0755, true);
}

// Override storage and cache path environment variables for serverless execution
$_ENV['APP_STORAGE_PATH'] = $storageDir;
putenv("APP_STORAGE_PATH={$storageDir}");

require __DIR__ . '/../public/index.php';

