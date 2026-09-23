<?php

use App\Http\Controllers\ReadmeController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

// Landing page + builder UI — only on the main instance (ENABLE_UI=true).
// Forked deployments default to ENABLE_UI=false and are API-only, so the
// owner keeps exclusive access to the landing page. NOTE: keep the route
// names registered in both branches so route('home') / route('builder')
// calls inside Blade views never throw RouteNotFoundException.
if (config('services.github.enable_ui', false)) {
    Route::get('/', [ReadmeController::class, 'landing'])->name('home');
    Route::get('/builder', [ReadmeController::class, 'builder'])->name('builder');
} else {
    Route::get('/', function () {
        return response()->json([
            'status' => 'api-only',
            'message' => 'This is an API-only instance. The landing page and builder UI are disabled here and only available on the main instance.',
            'ui' => config('services.github.ui_url'),
            'repo' => config('services.github.repo_url', 'https://github.com/codebykenth/profilr'),
            'endpoints' => ['/api/stats', '/api/languages', '/api/streak', '/api/profile', '/api/pinned', '/api/readme'],
        ], 404);
    })->name('home');

    Route::get('/builder', function () {
        abort(404, 'Builder UI is disabled on API-only instances.');
    })->name('builder');
}

// Widget SVG endpoints (only registered if ENABLE_API=true in self-hosted deployments)
if (config('services.github.enable_api', false)) {
    Route::get('/stats', [WidgetController::class, 'stats'])->name('widget.stats');
    Route::get('/languages', [WidgetController::class, 'languages'])->name('widget.languages');
    Route::get('/streak', [WidgetController::class, 'streak'])->name('widget.streak');
    Route::get('/snake', [WidgetController::class, 'snake'])->name('widget.snake');
    Route::get('/profile', [WidgetController::class, 'profile'])->name('widget.profile');
    Route::get('/pinned', [WidgetController::class, 'pinned'])->name('widget.pinned');
    Route::get('/trophies', [WidgetController::class, 'trophies'])->name('widget.trophies');
    Route::get('/readme', [ReadmeController::class, 'generate'])->name('readme.generate');
} else {
    Route::any('/api/{any?}', function () {
        return response()->json([
            'status' => 'disabled',
            'message' => 'API endpoints are disabled on this server to protect Vercel execution & GitHub API rate limits. The profile generator produces 100% ready-to-use copy-paste markdown using direct CDN services, or you can self-host this repository to run your own API.',
            'repo' => config('services.github.repo_url', 'https://github.com/codebykenth/profilr'),
        ], 403);
    })->where('any', '.*');
}
