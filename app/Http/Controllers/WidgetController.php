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
     */
    public function trophies(Request $request): Response
    {
        return $this->renderWidget($request, 'trophies', function (string $username, array $theme) {
            $data = $this->github->getUserStats($username ?: null);
            $profile = $this->github->getUserProfile($username ?: null);

            $stats = [
                ['icon' => self::ICONS['star'], 'title' => 'Total Stars Earned', 'value' => number_format($data['totalStars']), 'count' => $data['totalStars']],
                ['icon' => self::ICONS['commit'], 'title' => 'Total Commits', 'value' => number_format($data['totalCommits']), 'count' => $data['totalCommits']],
                ['icon' => self::ICONS['pr'], 'title' => 'Total Pull Requests', 'value' => number_format($data['totalPRs']), 'count' => $data['totalPRs']],
                ['icon' => self::ICONS['issue'], 'title' => 'Total Issues', 'value' => number_format($data['totalIssues']), 'count' => $data['totalIssues']],
                ['icon' => self::ICONS['commit'], 'title' => 'Contributions (Year)', 'value' => number_format($data['totalContributions']), 'count' => $data['totalContributions']],
                ['icon' => self::ICONS['star'], 'title' => 'Followers', 'value' => number_format($profile['followers']), 'count' => $profile['followers']],
            ];

            $cols = 3;
            $tileW = 200;
            $tileH = 88;
            $gapX = 14;
            $gapY = 14;
            $trophies = [];
            foreach ($stats as $index => $stat) {
                [$rank, $rankColor] = $this->trophyRank($stat['count']);

                $trophies[] = [
                    'x' => ($index % $cols) * ($tileW + $gapX),
                    'y' => intdiv($index, $cols) * ($tileH + $gapY),
                    'icon' => $stat['icon'],
                    'rank' => $rank,
                    'rankColor' => $rankColor,
                    'title' => $stat['title'],
                    'value' => $stat['value'],
                ];
            }

            $rows = (int) ceil(count($trophies) / $cols);
            $cardW = 40 + ($cols * $tileW) + (($cols - 1) * $gapX) + 40;
            $cardH = 70 + ($rows * $tileH) + (($rows - 1) * $gapY) + 30;

            return view('widgets.trophies', [
                'theme' => $theme,
                'username' => $profile['login'] ?: 'GitHub',
                'trophies' => $trophies,
                'tileW' => $tileW,
                'tileH' => $tileH,
                'cardW' => $cardW,
                'cardH' => $cardH,
                'padX' => 40,
                'padY' => 70,
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

            return $snakeService->render([
                'calendar' => $calendar,
                'username' => $username ?: 'GitHub',
            ], $themeName === 'light' ? 'light' : 'dark');
        });
    }

    /**
     * Map a metric count to a trophy rank letter and color.
     *
     * @return array{0: string, 1: string}
     */
    private function trophyRank(int $count): array
    {
        $tiers = [
            ['S', 5000, '#f8d847'],
            ['A', 1000, '#e3b341'],
            ['B', 200, '#a0a0a0'],
            ['C', 20, '#8b7355'],
        ];

        foreach ($tiers as [$letter, $min, $color]) {
            if ($count >= $min) {
                return [$letter, $color];
            }
        }

        return ['D', '#6c757d'];
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
