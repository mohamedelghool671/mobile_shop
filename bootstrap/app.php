<?php

use App\Helpers\ApiResponse;
use App\Http\Middleware\EmailVerify;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verfiy_email' => EmailVerify::class,
            'admin' => AdminMiddleware::class
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/pay/webhook',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e,Request $request) {
            if ($request->expectsJson()) {
                return ApiResponse::sendResponse("record not found",422);
            }
            throw $e;
        });
    })
    ->create();
