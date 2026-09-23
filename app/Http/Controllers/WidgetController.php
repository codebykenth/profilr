<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use App\Services\GithubSnakeService;
use App\Services\ThemeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WidgetController extends Controller
{
    private const ICONS = [
        'star' => 'M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25z',
        'commit' => 'M11.93 8.5a4.002 4.002 0 01-7.86 0H.75a.75.75 0 010-1.5h3.32a4.002 4.002 0 017.86 0h3.32a.75.75 0 010 1.5h-3.32zm-1.43-.75a2.5 2.5 0 10-5 0 2.5 2.5 0 005 0z',
        'pr' => 'M7.177 3.073L9.573.677A.25.25 0 0110 .854v4.792a.25.25 0 01-.427.177L7.177 3.427a.25.25 0 010-.354zM3.75 2.5a.75.75 0 100 1.5.75.75 0 000-1.5zm-2.25.75a2.25 2.25 0 113 2.122v5.256a2.251 2.251 0 11-1.5 0V5.372A2.25 2.25 0 011.5 3.25zM11 2.5h-1V4h1a1 1 0 011 1v5.628a2.251 2.251 0 101.5 0V5A2.5 2.5 0 0011 2.5zm1 10.25a.75.75 0 111.5 0 .75.75 0 01-1.5 0zM3.75 12a.75.75 0 100 1.5.75.75 0 000-1.5z',
        'issue' => 'M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z M8 0a8 8 0 100 16A8 8 0 008 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z',
    ];

    public function __construct(
        private GitHubService $github,
    ) {}

    /**
     * GET /api/stats — GitHub stats card SVG.
     */
    public function stats(Request $request): Response
    {
        return $this->renderWidget($request, 'stats-card', function (string $username, array $theme) {
            $data = $this->github->getUserStats($username ?: null);
            $profile = $this->github->getUserProfile($username ?: null);

            return view('widgets.stats-card', [
                'theme' => $theme,
                'stats' => [
                    'totalStars' => number_format($data['totalStars']),
                    'totalCommits' => number_format($data['totalCommits']),
                    'totalPRs' => number_format($data['totalPRs']),
                    'totalIssues' => number_format($data['totalIssues']),
                    'totalContributions' => number_format($data['totalContributions']),
                ],
                'username' => $profile['login'],
                'name' => $profile['name'] ?: $profile['login'],
            ])->render();
        });
    }

    /**
     * GET /api/languages — Top languages SVG, or the raw breakdown when ?format=json.
     */
    public function languages(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        if ($request->query('format') === 'json') {
            return $this->languagesJson($request);
        }

        return $this->renderWidget($request, 'top-languages', function (string $username, array $theme) use ($request) {
            $limit = min((int) $request->query('limit', 8), 12);
            $data = $this->github->getTopLanguages($username ?: null, $limit);

            $langArray = array_values($data);
            $count = count($langArray);
            $barWidth = 450;
            $listTop = 80;
            $rowHeight = 30;
            $bottomPad = 18;
            $cols = 2;
            $colWidth = 225;
            $rows = max(1, (int) ceil($count / $cols));
            $cardHeight = $count > 0
                ? $listTop + (($rows - 1) * $rowHeight) + 16 + $bottomPad
                : $listTop + $bottomPad;

            $offset = 0.0;
            $barItems = [];
            foreach ($langArray as $index => $lang) {
                $width = ($lang['percentage'] / 100) * $barWidth;
                $barItems[] = [
                    'x' => $offset,
                    'width' => $width,
                    'color' => $lang['color'],
                    'delay' => $index * 80,
                ];
                $offset += $width;
            }

            $listItems = [];
            foreach ($langArray as $index => $lang) {
                // Column-major fill: top half down the left column, rest down the right.
                // 8 languages => 4 rows left + 4 rows right.
                $col = (int) floor($index / $rows);
                $row = $index % $rows;
                $listItems[] = [
                    'x' => $col * $colWidth,
                    'y' => $row * $rowHeight,
                    'delay' => $index * 100,
                    'color' => $lang['color'],
                    'name' => mb_strimwidth($lang['name'], 0, 16, '…'),
                    'percentage' => $lang['percentage'],
                ];
            }

            return view('widgets.top-languages', [
                'theme' => $theme,
                'cardHeight' => $cardHeight,
                'barWidth' => $barWidth,
                'barItems' => $barItems,
                'listItems' => $listItems,
            ])->render();
        });
    }

    /**
     * GET /api/languages?format=json — Same language breakdown as the SVG widget,
     * consumed by the builder's auto-detection so the tech stack matches the widget.
     */
    private function languagesJson(Request $request): JsonResponse
    {
        $username = (string) $request->query('username', '');
        $headers = ['Access-Control-Allow-Origin' => '*'];

        if (empty(config('services.github.token'))) {
            return response()->json(['error' => 'Configuration Required'], 503, $headers);
        }

        try {
            $limit = min((int) $request->query('limit', 12), 12);
            $data = $this->github->getTopLanguages($username ?: null, $limit);

            return response()->json(array_values($data), 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Failed to fetch data from GitHub.',
            ], 500, $headers);
        }
    }

    /**
     * GET /api/streak — Contribution streak SVG.
     */
    public function streak(Request $request): Response
    {
        return $this->renderWidget($request, 'streak-stats', function (string $username, array $theme) {
            $data = $this->github->getStreakStats($username ?: null);

            $formatDate = function (?string $dateStr): string {
                if (empty($dateStr)) {
                    return 'N/A';
                }
                try {
                    return Carbon::parse($dateStr)->format('M j, Y');
                } catch (\Exception) {
                    return 'N/A';
                }
            };

            return view('widgets.streak-stats', [
                'theme' => $theme,
                'streak' => [
                    'currentStreak' => $data['currentStreak'],
                    'currentRange' => $formatDate($data['currentStreakStart']).' - '.$formatDate($data['currentStreakEnd']),
                    'longestStreak' => $data['longestStreak'],
                    'longestRange' => $formatDate($data['longestStreakStart']).' - '.$formatDate($data['longestStreakEnd']),
                    'currentYearContributions' => number_format($data['currentYearContributions']),
                ],
            ])->render();
        });
    }

    /**
     * GET /api/profile — Profile card SVG.
     */
    public function profile(Request $request): Response
    {
        return $this->renderWidget($request, 'profile-card', function (string $username, array $theme) {
            $data = $this->github->getUserProfile($username ?: null);

            return view('widgets.profile-card', [
                'theme' => $theme,
                'profile' => [
                    'displayName' => $data['name'] ?: $data['login'],
                    'login' => $data['login'],
                    'bio' => $data['bio'] ? mb_strimwidth($data['bio'], 0, 60, '...') : 'No bio available',
                    'avatarUrl' => $data['avatarUrl'] ?? '',
                    'followers' => number_format($data['followers']),
                    'following' => number_format($data['following']),
                    'repositories' => number_format($data['repositories']),
                    'memberSince' => ! empty($data['createdAt']) ? Carbon::parse($data['createdAt'])->format('M Y') : '',
                ],
            ])->render();
        });
    }

    /**
     * GET /api/pinned — Pinned repos SVG.
     */
    public function pinned(Request $request): Response
    {
        return $this->renderWidget($request, 'pinned-repos', function (string $username, array $theme) {
            $data = $this->github->getPinnedRepos($username ?: null);

            $repoCount = count($data);
            $cols = min($repoCount, 2);
            $rows = (int) ceil($repoCount / 2);
            $cardW = 230;
            $cardH = 120;
            $gap = 15;
            $svgWidth = ($cols * $cardW) + (($cols - 1) * $gap) + 50;
            $svgHeight = ($rows * $cardH) + (($rows - 1) * $gap) + 70;

            $formattedRepos = [];
            foreach ($data as $index => $repo) {
                $col = $index % 2;
                $row = intdiv($index, 2);
                $formattedRepos[] = [
                    'name' => $repo['name'],
                    'x' => $col * ($cardW + $gap),
                    'y' => $row * ($cardH + $gap),
                    'delay' => $index * 150,
                    'description' => $repo['description'] ? mb_strimwidth($repo['description'], 0, 50, '...') : 'No description',
                    'language' => $repo['language'],
                    'languageColor' => $repo['languageColor'],
                    'stars' => number_format($repo['stars']),
                    'forks' => number_format($repo['forks']),
                ];
            }

            return view('widgets.pinned-repos', [
                'theme' => $theme,
                'cardW' => $cardW,
                'cardH' => $cardH,
                'svgWidth' => $svgWidth,
                'svgHeight' => $svgHeight,
                'repos' => $formattedRepos,
            ])->render();
        });
    }

    /**
     * GET /api/trophies — Self-hosted GitHub trophies SVG (uses this instance's GITHUB_TOKEN).
     *
     * Mirrors the classic github-profile-trophy UI: one 110x110 panel per trophy
     * with a cup icon, rank letter, rank title, score and next-rank progress bar.
     * Supports the same query params: column, row, margin-w, margin-h,
     * no-bg, no-frame, title, rank.
     */
    public function trophies(Request $request): Response
    {
        return $this->renderWidget($request, 'trophies', function (string $username, array $theme) use ($request) {
            $data = $this->github->getUserStats($username ?: null);
            $profile = $this->github->getUserProfile($username ?: null);

            $experienceScore = $this->trophyExperienceScore($profile['createdAt'] ?? '');

            $definitions = [
                ['key' => 'Stars', 'score' => (int) $data['totalStars'], 'conditions' => [
                    ['SSS', 'Super Stargazer', 2000], ['SS', 'High Stargazer', 700], ['S', 'Stargazer', 200],
                    ['AAA', 'Super Star', 100], ['AA', 'High Star', 50], ['A', 'You are a Star', 30],
                    ['B', 'Middle Star', 10], ['C', 'First Star', 1],
                ]],
                ['key' => 'Commits', 'score' => (int) $data['totalCommits'], 'conditions' => [
                    ['SSS', 'God Committer', 4000], ['SS', 'Deep Committer', 2000], ['S', 'Super Committer', 1000],
                    ['AAA', 'Ultra Committer', 500], ['AA', 'Hyper Committer', 200], ['A', 'High Committer', 100],
                    ['B', 'Middle Committer', 10], ['C', 'First Commit', 1],
                ]],
                ['key' => 'Followers', 'score' => (int) $profile['followers'], 'conditions' => [
                    ['SSS', 'Super Celebrity', 1000], ['SS', 'Ultra Celebrity', 400], ['S', 'Hyper Celebrity', 200],
                    ['AAA', 'Famous User', 100], ['AA', 'Active User', 50], ['A', 'Dynamic User', 20],
                    ['B', 'Many Friends', 10], ['C', 'First Friend', 1],
                ]],
                ['key' => 'Issues', 'score' => (int) $data['totalIssues'], 'conditions' => [
                    ['SSS', 'God Issuer', 1000], ['SS', 'Deep Issuer', 500], ['S', 'Super Issuer', 200],
                    ['AAA', 'Ultra Issuer', 100], ['AA', 'Hyper Issuer', 50], ['A', 'High Issuer', 20],
                    ['B', 'Middle Issuer', 10], ['C', 'First Issue', 1],
                ]],
                ['key' => 'PullRequest', 'score' => (int) $data['totalPRs'], 'conditions' => [
                    ['SSS', 'God Puller', 1000], ['SS', 'Deep Puller', 500], ['S', 'Super Puller', 200],
                    ['AAA', 'Ultra Puller', 100], ['AA', 'Hyper Puller', 50], ['A', 'High Puller', 20],
                    ['B', 'Middle Puller', 10], ['C', 'First Pull', 1],
                ]],
                ['key' => 'Repositories', 'score' => (int) $profile['repositories'], 'conditions' => [
                    ['SSS', 'God Repo Creator', 50], ['SS', 'Deep Repo Creator', 45], ['S', 'Super Repo Creator', 40],
                    ['AAA', 'Ultra Repo Creator', 35], ['AA', 'Hyper Repo Creator', 30], ['A', 'High Repo Creator', 20],
                    ['B', 'Middle Repo Creator', 10], ['C', 'First Repository', 1],
                ]],
                ['key' => 'Experience', 'score' => $experienceScore, 'conditions' => [
                    ['SSS', 'Seasoned Veteran', 70], ['SS', 'Grandmaster', 55], ['S', 'Master Dev', 40],
                    ['AAA', 'Expert Dev', 28], ['AA', 'Experienced Dev', 18], ['A', 'Intermediate Dev', 11],
                    ['B', 'Junior Dev', 6], ['C', 'Newbie', 2],
                ]],
            ];

            $trophies = [];
            foreach ($definitions as $def) {
                $trophies[] = $this->buildTrophy($def['key'], $def['score'], $def['conditions']);
            }

            // Filter by rank (?rank=S,AAA or ?rank=-C,-B — "?" denotes UNKNOWN).
            $rankParam = trim((string) $request->query('rank', ''));
            if ($rankParam !== '') {
                $trophies = $this->filterTrophiesByRank($trophies, $rankParam);
            }

            // Filter by title (?title=Stars,Followers or ?title=-Stars).
            $titleParam = trim((string) $request->query('title', ''));
            if ($titleParam !== '') {
                $trophies = $this->filterTrophiesByTitle($trophies, $titleParam);
            }

            // Sort best rank first (SSS → SS → S → AAA → … → UNKNOWN).
            $order = array_flip(['SECRET', 'SSS', 'SS', 'S', 'AAA', 'AA', 'A', 'B', 'C', '?']);
            usort($trophies, function ($a, $b) use ($order) {
                return ($order[$a['rank']] ?? 99) <=> ($order[$b['rank']] ?? 99);
            });

            $panel = 110;
            $maxColumn = (int) $request->query('column', 6);
            $maxRow = (int) $request->query('row', 3);
            $marginW = (int) ($request->query('margin-w', $request->query('margin_w', 0)));
            $marginH = (int) ($request->query('margin-h', $request->query('margin_h', 0)));
            $noBg = filter_var($request->query('no-bg', $request->query('no_bg', false)), FILTER_VALIDATE_BOOLEAN);
            $noFrame = filter_var($request->query('no-frame', $request->query('no_frame', false)), FILTER_VALIDATE_BOOLEAN);

            if ($maxColumn === -1) {
                $maxColumn = max(1, count($trophies));
                $maxRow = 1;
            }
            $maxColumn = max(1, min($maxColumn, 12));
            $maxRow = max(1, min($maxRow, 10));

            // Cap visible trophies to the requested grid.
            $trophies = array_slice(array_values($trophies), 0, $maxColumn * $maxRow);

            $cols = min(count($trophies), $maxColumn);
            $rows = $cols > 0 ? (int) ceil(count($trophies) / $maxColumn) : 0;
            $cardW = $cols > 0 ? ($panel * $cols) + ($marginW * ($cols - 1)) : $panel;
            $cardH = $rows > 0 ? ($panel * $rows) + ($marginH * ($rows - 1)) : $panel;

            $positioned = [];
            foreach ($trophies as $index => $trophy) {
                $col = $index % $maxColumn;
                $row = intdiv($index, $maxColumn);
                $trophy['x'] = ($panel * $col) + ($marginW * $col);
                $trophy['y'] = ($panel * $row) + ($marginH * $row);
                $positioned[] = $trophy;
            }

            return view('widgets.trophies', [
                'theme' => $theme,
                'trophies' => $positioned,
                'panel' => $panel,
                'cardW' => $cardW,
                'cardH' => $cardH,
                'noBg' => $noBg,
                'noFrame' => $noFrame,
            ])->render();
        });
    }

    /**
     * GET /api/snake — Animated GitHub contribution snake animation SVG.
     */
    public function snake(Request $request, GithubSnakeService $snakeService): Response
    {
        return $this->renderWidget($request, 'snake', function (string $username, array $theme) use ($snakeService, $request) {
            $calendar = $this->github->getContributionCalendar($username ?: null);
            $themeName = $request->query('theme', 'dark');
            $speed = strtolower((string) $request->query('speed', 'normal'));
            if (! in_array($speed, ['slow', 'normal', 'fast'], true)) {
                $speed = 'normal';
            }

            return $snakeService->render([
                'calendar' => $calendar,
                'username' => $username ?: 'GitHub',
            ], $themeName === 'light' ? 'light' : 'dark', $speed);
        });
    }

    /**
     * Build a single trophy with classic github-profile-trophy rank, messages and progress.
     *
     * @param  array<int, array{0: string, 1: string, 2: int}>  $conditions
     * @return array{title: string, score: int, rank: string, topMessage: string, bottomMessage: string, progress: float}
     */
    private function buildTrophy(string $title, int $score, array $conditions): array
    {
        $rank = '?';
        $topMessage = 'Unknown';
        $currentMin = 0;
        $nextMin = null;

        foreach ($conditions as $index => [$letter, $message, $min]) {
            if ($score >= $min) {
                $rank = $letter;
                $topMessage = $message;
                $currentMin = $min;
                $nextMin = $index > 0 ? $conditions[$index - 1][2] : null;

                break;
            }
        }

        if ($rank === '?') {
            $progress = 0.0;
        } elseif ($nextMin === null) {
            $progress = 1.0;
        } else {
            $distance = max(1, $nextMin - $currentMin);
            $progress = min(1.0, max(0.0, ($score - $currentMin) / $distance));
        }

        return [
            'title' => $title,
            'score' => $score,
            'rank' => $rank,
            'topMessage' => $topMessage,
            'bottomMessage' => $this->abridgeScore($score),
            'progress' => $progress,
        ];
    }

    /**
     * Format a trophy score like the upstream service (e.g. 1500 → 1.5k).
     */
    private function abridgeScore(int $score): string
    {
        if (abs($score) < 1) {
            return '0';
        }

        if (abs($score) > 999) {
            return sprintf('%s%.1fk', $score < 0 ? '-' : '', abs($score) / 1000);
        }

        return (string) $score;
    }

    /**
     * Experience score mirrors upstream: floor(account age in days / 100).
     */
    private function trophyExperienceScore(string $createdAt): int
    {
        if ($createdAt === '') {
            return 0;
        }

        try {
            $created = Carbon::parse($createdAt);
        } catch (\Exception) {
            return 0;
        }

        $days = max(0, (int) $created->diffInDays(Carbon::now()));

        return (int) floor($days / 100);
    }

    /**
     * Filter trophies by rank (?rank=S,AAA or ?rank=-C,-B — "?" denotes UNKNOWN).
     *
     * @param  array<int, array{rank: string}>  $trophies
     * @return array<int, array{rank: string}>
     */
    private function filterTrophiesByRank(array $trophies, string $param): array
    {
        $values = array_filter(array_map('trim', explode(',', $param)));
        if ($values === []) {
            return $trophies;
        }

        $isExclusion = str_starts_with($values[0], '-');
        $normalized = array_map(function ($value) {
            $value = ltrim($value, '-');

            return strtoupper($value) === 'UNKNOWN' ? '?' : strtoupper($value);
        }, $values);

        return array_values(array_filter($trophies, function ($trophy) use ($normalized, $isExclusion) {
            $included = in_array(strtoupper($trophy['rank']), $normalized, true);

            return $isExclusion ? ! $included : $included;
        }));
    }

    /**
     * Filter trophies by title (?title=Stars,Followers or ?title=-Stars).
     *
     * @param  array<int, array{title: string}>  $trophies
     * @return array<int, array{title: string}>
     */
    private function filterTrophiesByTitle(array $trophies, string $param): array
    {
        $values = array_filter(array_map('trim', explode(',', $param)));
        if ($values === []) {
            return $trophies;
        }

        $isExclusion = str_starts_with($values[0], '-');
        $normalized = array_map(function ($value) {
            return strtolower(ltrim($value, '-'));
        }, $values);

        $aliases = [
            'star' => 'stars',
            'commit' => 'commits',
            'follower' => 'followers',
            'issue' => 'issues',
            'pr' => 'pullrequest',
            'pulls' => 'pullrequest',
            'puller' => 'pullrequest',
            'repo' => 'repositories',
            'repository' => 'repositories',
            'experience' => 'experience',
            'duration' => 'experience',
            'since' => 'experience',
        ];

        $normalized = array_map(fn ($value) => $aliases[$value] ?? $value, $normalized);

        return array_values(array_filter($trophies, function ($trophy) use ($normalized, $isExclusion) {
            $included = in_array(strtolower($trophy['title']), $normalized, true);

            return $isExclusion ? ! $included : $included;
        }));
    }

    /**
     * Wrap widget rendering with error handling and consistent SVG response.
     */
    private function renderWidget(Request $request, string $widgetName, callable $renderer): Response
    {
        $username = $request->query('username', '');
        $theme = ThemeService::get($request->query('theme', 'light'));

        if (empty(config('services.github.token'))) {
            $svg = view('widgets.error', [
                'title' => 'Configuration Required',
                'message' => 'GITHUB_TOKEN environment variable is not set.',
                'hint' => 'Add your GitHub Personal Access Token to the environment.',
            ])->render();

            return $this->svgResponse($svg, 'error', $theme, 503);
        }

        try {
            $svg = $renderer($username, $theme);

            return $this->svgResponse($svg, $widgetName, $theme);
        } catch (\Exception $e) {
            $svg = view('widgets.error', [
                'title' => 'GitHub API Error',
                'message' => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Failed to fetch data from GitHub.',
                'hint' => 'Check your GITHUB_TOKEN and try again.',
            ])->render();

            return $this->svgResponse($svg, 'error', $theme, 500);
        }
    }

    /**
     * Load CSS from public/css and inject theme values.
     */
    private function getWidgetStyles(string $name, array $theme = []): string
    {
        $path = public_path("css/{$name}.css");
        if (! file_exists($path)) {
            return '';
        }

        $css = file_get_contents($path);

        foreach ($theme as $key => $value) {
            $css = str_replace([
                "var(--theme-{$key})",
                "{{theme.{$key}}}",
                "{{ \$theme['{$key}'] }}",
            ], $value, $css);
        }

        return $css;
    }

    /**
     * Return an SVG response with proper caching headers and inlined styles.
     */
    private function svgResponse(string $svg, string $widgetName = '', array $theme = [], int $status = 200): Response
    {
        if ($widgetName !== '' && ! str_contains($svg, '<style>')) {
            $styles = $this->getWidgetStyles($widgetName, $theme);
            if ($styles !== '') {
                $svg = preg_replace('/(<svg[^>]*>)/i', "$1\n    <style>\n".trim($styles)."\n    </style>", $svg, 1);
            }
        }

        if (! str_starts_with(trim($svg), '<?xml')) {
            $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$svg;
        }

        $cacheTtl = (int) config('services.github.cache_ttl', 1800);

        $headers = [
            'Content-Type' => 'image/svg+xml; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($status === 200) {
            $headers['Cache-Control'] = "public, max-age={$cacheTtl}, s-maxage={$cacheTtl}, stale-while-revalidate=86400";
        } else {
            $headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
        }

        return response($svg, $status, $headers);
    }
}
