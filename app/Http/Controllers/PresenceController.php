<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PresenceController extends Controller
{
    private const CACHE_KEY = 'presence:online';

    private const TTL_SECONDS = 90;

    private const WINDOW_SECONDS = 75;

    /**
     * GET /api/presence — heartbeat + live online count.
     *
     * Query param `visitor` is a client-generated UUID stored in localStorage.
     * Each heartbeat refreshes that visitor's last-seen timestamp; entries
     * older than the window are pruned so the count reflects users active
     * right now. Works on the configured cache store (no database needed).
     */
    public function show(Request $request): JsonResponse
    {
        $visitor = $this->normalizeVisitor((string) $request->query('visitor', ''));
        $now = time();

        /** @var array<string, int> $visitors */
        $visitors = Cache::get(self::CACHE_KEY, []);

        // Prune stale entries.
        foreach ($visitors as $id => $lastSeen) {
            if (($now - (int) $lastSeen) > self::WINDOW_SECONDS) {
                unset($visitors[$id]);
            }
        }

        if ($visitor !== '') {
            $visitors[$visitor] = $now;
            Cache::put(self::CACHE_KEY, $visitors, self::TTL_SECONDS);
        }

        return response()->json(
            ['online' => count($visitors)],
            200,
            ['Access-Control-Allow-Origin' => '*', 'Cache-Control' => 'no-store']
        );
    }

    /**
     * Keep visitor IDs safe for use as cache array keys (UUID-ish, max 64 chars).
     */
    private function normalizeVisitor(string $visitor): string
    {
        $visitor = trim($visitor);
        if ($visitor === '') {
            return '';
        }

        $cleaned = preg_replace('/[^A-Za-z0-9\-_]/', '', $visitor) ?? '';

        return substr($cleaned, 0, 64);
    }
}
