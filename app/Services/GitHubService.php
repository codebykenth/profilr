<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    private string $graphqlUrl;

    private string $token;

    private int $cacheTtl;

    public function __construct()
    {
        $this->graphqlUrl = config('services.github.graphql_url');
        $this->token = config('services.github.token', '');
        $this->cacheTtl = (int) config('services.github.cache_ttl', 1800);
    }

    /**
     * Get the authenticated user's login via the viewer query.
     */
    public function getAuthenticatedUsername(): ?string
    {
        $data = $this->query('{ viewer { login } }');

        return $data['viewer']['login'] ?? null;
    }

    /**
     * Fetch user profile data.
     *
     * @return array{login: string, name: string, avatarUrl: string, bio: string, company: string, location: string, websiteUrl: string, followers: int, following: int, repositories: int, createdAt: string}
     */
    public function getUserProfile(?string $username = null): array
    {
        $query = $username
            ? $this->userQuery($username, $this->profileFields())
            : $this->viewerQuery($this->profileFields());

        $data = $this->cachedQuery("profile:{$username}", $query);
        $user = $data['user'] ?? $data['viewer'] ?? [];

        return [
            'login' => $user['login'] ?? '',
            'name' => $user['name'] ?? '',
            'avatarUrl' => $user['avatarUrl'] ?? '',
            'bio' => $user['bio'] ?? '',
            'company' => $user['company'] ?? '',
            'location' => $user['location'] ?? '',
            'websiteUrl' => $user['websiteUrl'] ?? '',
            'followers' => $user['followers']['totalCount'] ?? 0,
            'following' => $user['following']['totalCount'] ?? 0,
            'repositories' => $user['repositories']['totalCount'] ?? 0,
            'createdAt' => $user['createdAt'] ?? '',
        ];
    }

    /**
     * Fetch user stats: total stars, commits, PRs, issues, contributions.
     *
     * @return array{totalStars: int, totalCommits: int, totalPRs: int, totalIssues: int, totalContributions: int}
     */
    public function getUserStats(?string $username = null): array
    {
        $fields = <<<'GRAPHQL'
            repositories(first: 100, ownerAffiliations: OWNER, orderBy: {field: STARGAZERS, direction: DESC}) {
                totalCount
                nodes {
                    stargazerCount
                }
            }
            contributionsCollection {
                totalCommitContributions
                totalPullRequestContributions
                totalIssueContributions
                contributionCalendar {
                    totalContributions
                }
            }
            pullRequests { totalCount }
            issues { totalCount }
        GRAPHQL;

        $query = $username
            ? $this->userQuery($username, $fields)
            : $this->viewerQuery($fields);

        $data = $this->cachedQuery("stats:{$username}", $query);
        $user = $data['user'] ?? $data['viewer'] ?? [];

        $totalStars = 0;
        foreach (($user['repositories']['nodes'] ?? []) as $repo) {
            $totalStars += $repo['stargazerCount'] ?? 0;
        }

        $contributions = $user['contributionsCollection'] ?? [];

        return [
            'totalStars' => $totalStars,
            'totalCommits' => $contributions['totalCommitContributions'] ?? 0,
            'totalPRs' => $user['pullRequests']['totalCount'] ?? 0,
            'totalIssues' => $user['issues']['totalCount'] ?? 0,
            'totalContributions' => $contributions['contributionCalendar']['totalContributions'] ?? 0,
        ];
    }

    /**
     * Languages GitHub reports that are not real programming languages and
     * should never count toward the "most used languages" widget.
     *
     * @var array<string, true>
     */
    private const NON_CODE_LANGUAGES = [
        'Git' => true,
        'Git Config' => true,
        'Git Ignore' => true,
        'Git Links' => true,
        'Git LFS Pointer' => true,
        'Markdown' => true,
        'Text' => true,
        'JSON' => true,
        'YAML' => true,
        'TOML' => true,
        'XML' => true,
        'CSV' => true,
        'TSV' => true,
        'INI' => true,
        'Properties' => true,
        'BibTeX' => true,
        'reStructuredText' => true,
        'Org' => true,
        'Textile' => true,
        'RDoc' => true,
        'Creole' => true,
        'MediaWiki' => true,
        'AsciiDoc' => true,
        'EditorConfig' => true,
    ];

    /**
     * Total language bytes beyond which a single repository is scaled down so
     * one oversized repo (e.g. committed build assets) cannot dominate the widget.
     */
    private const MAX_REPO_LANGUAGE_BYTES = 1_000_000;

    /**
     * Fetch top programming languages across user's repositories.
     *
     * Non-code languages (Git config, Markdown, JSON, etc.) are excluded and
     * oversized repositories are scaled down so the result reflects the user's
     * actual tech stack instead of generated assets.
     *
     * @return array<string, array{name: string, size: float, color: string, percentage: float}>
     */
    public function getTopLanguages(?string $username = null, int $limit = 8): array
    {
        $languages = [];

        foreach ($this->fetchRepositories($username) as $repo) {
            $edges = $repo['languages']['edges'] ?? [];
            if (empty($edges)) {
                continue;
            }

            // Scale oversized repositories down so a single repo with committed
            // build output (compiled CSS, bundles, etc.) cannot drown the stack.
            $repoTotal = array_sum(array_column($edges, 'size'));
            $scale = $repoTotal > self::MAX_REPO_LANGUAGE_BYTES
                ? self::MAX_REPO_LANGUAGE_BYTES / $repoTotal
                : 1.0;

            foreach ($edges as $edge) {
                $name = $edge['node']['name'] ?? '';
                if ($name === '' || isset(self::NON_CODE_LANGUAGES[$name])) {
                    continue;
                }

                if (! isset($languages[$name])) {
                    $languages[$name] = [
                        'name' => $name,
                        'size' => 0.0,
                        'color' => $edge['node']['color'] ?? '#858585',
                    ];
                }
                $languages[$name]['size'] += $edge['size'] * $scale;
            }
        }

        // Sort by size descending and take top N
        uasort($languages, fn ($a, $b) => $b['size'] <=> $a['size']);
        $languages = array_slice($languages, 0, $limit, true);

        // Calculate percentages
        $totalSize = array_sum(array_column($languages, 'size'));
        foreach ($languages as &$lang) {
            $lang['percentage'] = $totalSize > 0
                ? round(($lang['size'] / $totalSize) * 100, 1)
                : 0;
        }

        return $languages;
    }

    /**
     * Fetch the user's own repositories (paginated) with their language breakdown.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchRepositories(?string $username): array
    {
        $fields = <<<'GRAPHQL'
            pageInfo {
                hasNextPage
                endCursor
            }
            nodes {
                languages(first: 10, orderBy: {field: SIZE, direction: DESC}) {
                    edges {
                        size
                        node {
                            name
                            color
                        }
                    }
                }
            }
        GRAPHQL;

        $repositories = [];
        $cursor = null;
        $maxPages = 3;

        for ($page = 0; $page < $maxPages; $page++) {
            $pagination = $cursor !== null ? ", after: \"{$cursor}\"" : '';
            $repoFields = "repositories(first: 100, ownerAffiliations: OWNER, orderBy: {field: PUSHED_AT, direction: DESC}, isFork: false{$pagination}) { {$fields} }";

            $query = $username
                ? $this->userQuery($username, $repoFields)
                : $this->viewerQuery($repoFields);

            $data = $this->cachedQuery("languages:{$username}:{$page}", $query);
            $user = $data['user'] ?? $data['viewer'] ?? [];

            $repositories = array_merge($repositories, $user['repositories']['nodes'] ?? []);

            $pageInfo = $user['repositories']['pageInfo'] ?? [];
            if (empty($pageInfo['hasNextPage']) || empty($pageInfo['endCursor'])) {
                break;
            }

            $cursor = $pageInfo['endCursor'];
        }

        return $repositories;
    }

    /**
     * Fetch contribution streak data.
     *
     * @return array{currentStreak: int, longestStreak: int, totalContributions: int, currentYearContributions: int, currentStreakStart: string, currentStreakEnd: string, longestStreakStart: string, longestStreakEnd: string}
     */
    public function getStreakStats(?string $username = null): array
    {
        $now = now();
        $oneYearAgo = $now->copy()->subYear();

        $fields = <<<GRAPHQL
            contributionsCollection(from: "{$oneYearAgo->toIso8601String()}", to: "{$now->toIso8601String()}") {
                contributionCalendar {
                    totalContributions
                    weeks {
                        contributionDays {
                            contributionCount
                            date
                        }
                    }
                }
            }
        GRAPHQL;

        $query = $username
            ? $this->userQuery($username, $fields)
            : $this->viewerQuery($fields);

        $data = $this->cachedQuery("streak:{$username}", $query);
        $user = $data['user'] ?? $data['viewer'] ?? [];

        $calendar = $user['contributionsCollection']['contributionCalendar'] ?? [];
        $days = [];
        foreach (($calendar['weeks'] ?? []) as $week) {
            foreach (($week['contributionDays'] ?? []) as $day) {
                $days[] = $day;
            }
        }

        return $this->calculateStreaks($days, $calendar['totalContributions'] ?? 0);
    }

    /**
     * Fetch user contribution calendar for snake animation.
     *
     * @return array{totalContributions: int, weeks: array<int, mixed>, months: array<int, mixed>}
     */
    public function getContributionCalendar(?string $username = null): array
    {
        $fields = <<<'GRAPHQL'
            contributionsCollection {
                contributionCalendar {
                    totalContributions
                    weeks {
                        contributionDays {
                            contributionCount
                            date
                            color
                            weekday
                        }
                    }
                    months {
                        name
                        year
                        firstDay
                        totalWeeks
                    }
                }
            }
        GRAPHQL;

        $query = $username
            ? $this->userQuery($username, $fields)
            : $this->viewerQuery($fields);

        $data = $this->cachedQuery("calendar:{$username}", $query);
        $user = $data['user'] ?? $data['viewer'] ?? [];

        return $user['contributionsCollection']['contributionCalendar'] ?? [
            'totalContributions' => 0,
            'weeks' => [],
            'months' => [],
        ];
    }

    /**
     * Fetch pinned repositories.
     *
     * @return array<int, array{name: string, description: string, url: string, language: string, languageColor: string, stars: int, forks: int}>
     */
    public function getPinnedRepos(?string $username = null): array
    {
        $fields = <<<'GRAPHQL'
            pinnedItems(first: 6, types: [REPOSITORY]) {
                nodes {
                    ... on Repository {
                        name
                        description
                        url
                        stargazerCount
                        forkCount
                        primaryLanguage {
                            name
                            color
                        }
                    }
                }
            }
        GRAPHQL;

        $query = $username
            ? $this->userQuery($username, $fields)
            : $this->viewerQuery($fields);

        $data = $this->cachedQuery("pinned:{$username}", $query);
        $user = $data['user'] ?? $data['viewer'] ?? [];

        $repos = [];
        foreach (($user['pinnedItems']['nodes'] ?? []) as $repo) {
            $repos[] = [
                'name' => $repo['name'] ?? '',
                'description' => $repo['description'] ?? '',
                'url' => $repo['url'] ?? '',
                'language' => $repo['primaryLanguage']['name'] ?? '',
                'languageColor' => $repo['primaryLanguage']['color'] ?? '#858585',
                'stars' => $repo['stargazerCount'] ?? 0,
                'forks' => $repo['forkCount'] ?? 0,
            ];
        }

        return $repos;
    }

    /**
     * Calculate current and longest streaks from contribution days.
     */
    private function calculateStreaks(array $days, int $totalContributions): array
    {
        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;
        $currentStreakStart = '';
        $currentStreakEnd = '';
        $longestStreakStart = '';
        $longestStreakEnd = '';
        $tempStreakStart = '';

        foreach ($days as $day) {
            if ($day['contributionCount'] > 0) {
                if ($tempStreak === 0) {
                    $tempStreakStart = $day['date'];
                }
                $tempStreak++;

                if ($tempStreak > $longestStreak) {
                    $longestStreak = $tempStreak;
                    $longestStreakStart = $tempStreakStart;
                    $longestStreakEnd = $day['date'];
                }
            } else {
                $tempStreak = 0;
            }
        }

        // Current streak: count backwards from today
        $currentStreak = 0;
        $reversedDays = array_reverse($days);
        // Skip today if no contributions yet (it's still ongoing)
        $startIndex = 0;
        if (! empty($reversedDays) && $reversedDays[0]['contributionCount'] === 0) {
            $startIndex = 1;
        }

        for ($i = $startIndex; $i < count($reversedDays); $i++) {
            if ($reversedDays[$i]['contributionCount'] > 0) {
                $currentStreak++;
                $currentStreakStart = $reversedDays[$i]['date'];
                if ($currentStreak === 1) {
                    $currentStreakEnd = $reversedDays[$i]['date'];
                }
            } else {
                break;
            }
        }

        // Contributions within the current calendar year (Jan 1 → today), taken
        // from the trailing-year calendar so no extra API request is needed.
        $currentYearPrefix = now()->year.'-';
        $currentYearContributions = 0;
        foreach ($days as $day) {
            if (isset($day['date']) && str_starts_with($day['date'], $currentYearPrefix)) {
                $currentYearContributions += (int) ($day['contributionCount'] ?? 0);
            }
        }

        return [
            'currentStreak' => $currentStreak,
            'longestStreak' => $longestStreak,
            'totalContributions' => $totalContributions,
            'currentYearContributions' => $currentYearContributions,
            'currentStreakStart' => $currentStreakStart,
            'currentStreakEnd' => $currentStreakEnd,
            'longestStreakStart' => $longestStreakStart,
            'longestStreakEnd' => $longestStreakEnd,
        ];
    }

    private function profileFields(): string
    {
        return <<<'GRAPHQL'
            login
            name
            avatarUrl
            bio
            company
            location
            websiteUrl
            createdAt
            followers { totalCount }
            following { totalCount }
            repositories(ownerAffiliations: OWNER) { totalCount }
        GRAPHQL;
    }

    private function userQuery(string $username, string $fields): string
    {
        return "{ user(login: \"{$username}\") { {$fields} } }";
    }

    private function viewerQuery(string $fields): string
    {
        return "{ viewer { {$fields} } }";
    }

    private function cachedQuery(string $key, string $query): array
    {
        $cacheKey = 'github:'.md5($key.$query);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($query) {
            return $this->query($query);
        });
    }

    /**
     * Execute a GraphQL query against the GitHub API.
     *
     * @return array<string, mixed>
     */
    private function query(string $query): array
    {
        $response = Http::withToken($this->token)
            ->post($this->graphqlUrl, ['query' => $query]);

        if ($response->failed()) {
            throw new \RuntimeException(
                "GitHub API error: {$response->status()} - {$response->body()}"
            );
        }

        $data = $response->json('data', []);

        if ($errors = $response->json('errors')) {
            throw new \RuntimeException(
                'GitHub GraphQL error: '.json_encode($errors)
            );
        }

        return $data;
    }
}
