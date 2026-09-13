<?php

// Forward static assets if requested
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__.'/../public'.$uri)) {
    return false;
}

// Ensure storage and cache directories exist in Vercel writable /tmp
$storageDir = '/tmp/storage';
$bootstrapCacheDir = '/tmp/bootstrap/cache';
if (! file_exists($storageDir)) {
    @mkdir($storageDir.'/app/public', 0755, true);
    @mkdir($storageDir.'/framework/views', 0755, true);
    @mkdir($storageDir.'/framework/cache/data', 0755, true);
    @mkdir($storageDir.'/framework/sessions', 0755, true);
    @mkdir($storageDir.'/logs', 0755, true);
}
if (! file_exists($bootstrapCacheDir)) {
    @mkdir($bootstrapCacheDir, 0755, true);
}

putenv('VERCEL=1');
putenv("APP_SERVICES_CACHE={$bootstrapCacheDir}/services.php");
putenv("APP_PACKAGES_CACHE={$bootstrapCacheDir}/packages.php");
putenv("APP_ROUTES_CACHE={$bootstrapCacheDir}/routes-v7.php");
putenv("APP_CONFIG_CACHE={$bootstrapCacheDir}/config.php");

// Fallback SQLite database for Vercel if PostgreSQL is not configured
$sqliteDb = '/tmp/database.sqlite';
if (! file_exists($sqliteDb)) {
    @touch($sqliteDb);
}

// Set required fallback environment variables if not defined in Vercel dashboard
if (empty($_ENV['APP_KEY']) && empty(getenv('APP_KEY'))) {
    putenv('APP_KEY=base64:B/NCyXoOTtrQMolZ77gou1CxHMvQeHyVPAMbepRHD0c=');
    $_ENV['APP_KEY'] = 'base64:B/NCyXoOTtrQMolZ77gou1CxHMvQeHyVPAMbepRHD0c=';
}

if (empty($_ENV['DB_CONNECTION']) && empty(getenv('DB_CONNECTION'))) {
    putenv('DB_CONNECTION=sqlite');
    putenv("DB_DATABASE={$sqliteDb}");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $sqliteDb;
}

$_ENV['CACHE_STORE'] = $_ENV['CACHE_STORE'] ?? getenv('CACHE_STORE') ?: 'array';
$_ENV['CACHE_DRIVER'] = $_ENV['CACHE_DRIVER'] ?? getenv('CACHE_DRIVER') ?: 'array';
$_ENV['SESSION_DRIVER'] = $_ENV['SESSION_DRIVER'] ?? getenv('SESSION_DRIVER') ?: 'cookie';

$_SERVER['CACHE_STORE'] = $_ENV['CACHE_STORE'];
$_SERVER['CACHE_DRIVER'] = $_ENV['CACHE_DRIVER'];
$_SERVER['SESSION_DRIVER'] = $_ENV['SESSION_DRIVER'];

putenv('CACHE_STORE=array');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');

if (empty($_ENV['APP_MAINTENANCE_DRIVER']) && empty(getenv('APP_MAINTENANCE_DRIVER'))) {
    putenv('APP_MAINTENANCE_DRIVER=file');
    $_ENV['APP_MAINTENANCE_DRIVER'] = 'file';
    $_SERVER['APP_MAINTENANCE_DRIVER'] = 'file';
}

putenv("APP_STORAGE_PATH={$storageDir}");
putenv("LARAVEL_STORAGE_PATH={$storageDir}");
putenv("VIEW_COMPILED_PATH={$storageDir}/framework/views");

$_ENV['LARAVEL_STORAGE_PATH'] = $storageDir;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storageDir;
$_ENV['VIEW_COMPILED_PATH'] = "{$storageDir}/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storageDir}/framework/views";

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $e) {
    http_response_code(200);
    header('Content-Type: text/html');
    echo '<style>body{font-family:sans-serif;padding:20px;background:#f8fafc;color:#0f172a}pre{background:#1e293b;color:#f8fafc;padding:15px;border-radius:8px;overflow-x:auto}</style>';
    echo '<h1>Laravel Vercel Runtime Exception</h1>';
    echo '<p><strong>Message:</strong> '.htmlspecialchars($e->getMessage()).'</p>';
    echo '<p><strong>File:</strong> '.htmlspecialchars($e->getFile()).' (Line '.$e->getLine().')</p>';
    echo '<h3>Stack Trace:</h3><pre>'.htmlspecialchars($e->getTraceAsString()).'</pre>';
}
