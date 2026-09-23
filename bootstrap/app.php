<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel terminates TLS at its edge proxy and forwards plain HTTP to the PHP runtime. Trust the proxy so Laravel honors X-Forwarded-Proto and generates https:// URLs for asset() and getSchemeAndHttpHost().
        $middleware->trustProxies(
            '*',
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_HOST
        );
    })
    ->withProviders()
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
