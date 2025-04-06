<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
            if ($request->expectsJson() || !$request->isMethod('GET')) {
                return null; // Let Laravel handle it normally
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return \Inertia\Inertia::render('Errors/NotFound')
                    ->toResponse($request)
                    ->setStatusCode(404);
            }

            if (
                $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException
                && $e->getStatusCode() === 500
            ) {
                return \Inertia\Inertia::render('Errors/ServerError')
                    ->toResponse($request)
                    ->setStatusCode(500);
            }

            return null; // Fall back to Laravel's default handler
        });
    })->create();
