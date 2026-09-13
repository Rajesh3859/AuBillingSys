<?php

// Forward static assets if requested
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Ensure storage and cache directories exist in Vercel writable /tmp
$storageDir = '/tmp/storage';
if (!file_exists($storageDir)) {
    @mkdir($storageDir . '/app/public', 0755, true);
    @mkdir($storageDir . '/framework/views', 0755, true);
    @mkdir($storageDir . '/framework/cache/data', 0755, true);
    @mkdir($storageDir . '/framework/sessions', 0755, true);
    @mkdir($storageDir . '/logs', 0755, true);
    @mkdir('/tmp/bootstrap/cache', 0755, true);
}

putenv("APP_STORAGE_PATH={$storageDir}");
putenv("VIEW_COMPILED_PATH={$storageDir}/framework/views");

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html');
    echo "<h2>Laravel Vercel Runtime Error</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

