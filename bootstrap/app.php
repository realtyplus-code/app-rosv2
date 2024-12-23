<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        //api: __DIR__.'/../routes/api.php',
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/web/property.php',
            __DIR__ . '/../routes/web/user_property.php',
            __DIR__ . '/../routes/web/enum.php',
            __DIR__ . '/../routes/web/user.php',
            __DIR__ . '/../routes/web/role.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
