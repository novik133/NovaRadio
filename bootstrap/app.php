<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Ensure required storage directories exist before any service provider registers.
// This prevents "Please provide a valid cache path" errors when ViewServiceProvider
// tries to resolve the compiled views path on a fresh deploy or after an update.
$storageDirs = [
    dirname(__DIR__) . '/storage/app/public',
    dirname(__DIR__) . '/storage/framework/cache/data',
    dirname(__DIR__) . '/storage/framework/sessions',
    dirname(__DIR__) . '/storage/framework/testing',
    dirname(__DIR__) . '/storage/framework/views',
    dirname(__DIR__) . '/storage/logs',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'install',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response, \Throwable $e, \Illuminate\Http\Request $request) {
            if ($response->getStatusCode() === 419) {
                return redirect()->back()->with('error', __('admin.messages.session_expired'));
            }
            return $response;
        });
    })->create();
