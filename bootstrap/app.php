<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Vercel's serverless filesystem is read-only except /tmp, and the legacy
// `env` block in vercel.json is ignored by Vercel — so clones ship without
// APP_CONFIG_CACHE-style paths, array drivers, or stderr logging unless the
// user adds every var manually in the dashboard. Default the purely
// runtime-safe values here when running on Vercel (Vercel always sets
// VERCEL=1); GITHUB_TOKEN and ENABLE_API must still come from the
// dashboard — APP_KEY is derived automatically below when missing.
if (getenv('VERCEL')) {
    foreach ([
        'VIEW_COMPILED_PATH' => '/tmp/views',
        'CACHE_STORE' => 'array',
        'SESSION_DRIVER' => 'array',
        'LOG_CHANNEL' => 'stderr',
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'APP_CONFIG_CACHE' => '/tmp/config.php',
        'APP_EVENTS_CACHE' => '/tmp/events.php',
        'APP_PACKAGES_CACHE' => '/tmp/packages.php',
        'APP_ROUTES_CACHE' => '/tmp/routes.php',
        'APP_SERVICES_CACHE' => '/tmp/services.php',
        'ENABLE_API' => true,
        'ENABLE_UI' => false,
    ] as $key => $fallback) {
        if (getenv($key) === false || getenv($key) === '') {
            putenv("{$key}={$fallback}");
            $_ENV[$key] = $fallback;
            $_SERVER[$key] = $fallback;
        }
    }
}

// One-click deploys only ask for GITHUB_TOKEN, so APP_KEY is derived
// automatically when missing instead of 500ing. The key is deterministic
// per deployment (SHA-256 of the GitHub token), which keeps it stable
// across Vercel's serverless instances/cold starts. This is safe here
// because the app is stateless — no logins, database, or encrypted data.
// Override with a real APP_KEY in the dashboard for hardening.
if (getenv('APP_KEY') === false || getenv('APP_KEY') === '') {
    $token = getenv('GITHUB_TOKEN') ?: '';
    $raw = $token !== '' ? hash('sha256', 'profilr-app-key:'.$token, true) : random_bytes(32);
    $derived = 'base64:'.base64_encode($raw);
    putenv("APP_KEY={$derived}");
    $_ENV['APP_KEY'] = $derived;
    $_SERVER['APP_KEY'] = $derived;
}

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
