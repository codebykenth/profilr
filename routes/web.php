<?php

use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ReadmeController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [ReadmeController::class, 'landing'])->name('home');

// Interactive Profile & README Builder
Route::get('/builder', [ReadmeController::class, 'builder'])->name('builder');

// Live presence heartbeat — always enabled (no GitHub token needed, no DB needed).
Route::get('/api/presence', [PresenceController::class, 'show'])->name('api.presence');

// Widget SVG endpoints (only registered if ENABLE_API=true in self-hosted deployments)
if (config('services.github.enable_api', false)) {
    Route::prefix('api')->group(function () {
        Route::get('/stats', [WidgetController::class, 'stats'])->name('widget.stats');
        Route::get('/languages', [WidgetController::class, 'languages'])->name('widget.languages');
        Route::get('/streak', [WidgetController::class, 'streak'])->name('widget.streak');
        Route::get('/snake', [WidgetController::class, 'snake'])->name('widget.snake');
        Route::get('/profile', [WidgetController::class, 'profile'])->name('widget.profile');
        Route::get('/pinned', [WidgetController::class, 'pinned'])->name('widget.pinned');
        Route::get('/trophies', [WidgetController::class, 'trophies'])->name('widget.trophies');
        Route::get('/readme', [ReadmeController::class, 'generate'])->name('readme.generate');
    });
} else {
    Route::any('/api/{any?}', function () {
        return response()->json([
            'status' => 'disabled',
            'message' => 'API endpoints are disabled on this server to protect Vercel execution & GitHub API rate limits. The profile generator produces 100% ready-to-use copy-paste markdown using direct CDN services, or you can self-host this repository to run your own API.',
            'repo' => config('services.github.repo_url', 'https://github.com/codebykenth/profilr'),
        ], 403);
    })->where('any', '.*');
}
