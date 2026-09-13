<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL_ENV']) || getenv('VERCEL')) {
    $storagePath = '/tmp/storage';
    if (! is_dir($storagePath.'/framework/views')) {
        @mkdir($storagePath.'/framework/views', 0755, true);
        @mkdir($storagePath.'/framework/cache/data', 0755, true);
        @mkdir($storagePath.'/framework/sessions', 0755, true);
        @mkdir($storagePath.'/logs', 0755, true);
    }
    $app->useStoragePath($storagePath);
    $app->booting(function () {
        config([
            'cache.default' => 'array',
            'session.driver' => 'cookie',
            'view.compiled' => '/tmp/storage/framework/views',
            'app.maintenance.driver' => config('app.maintenance.driver') ?: 'file',
        ]);
    });
}

return $app;
