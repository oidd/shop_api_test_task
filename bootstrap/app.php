<?php

use App\Exceptions\UserCantAffordOrderException;
use App\Http\Middleware\AuthenticateTokenUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/general_api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'authenticate' => AuthenticateTokenUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(
            function (UserCantAffordOrderException $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getCode());
            }
        );
    })->create();
