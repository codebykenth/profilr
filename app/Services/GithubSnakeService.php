<?php

namespace App\Services;

class GithubSnakeService
{
    /**
     * Generate an animated SVG snake on top of the user's GitHub contribution grid.
     *
     * @param  array{
     *     calendar: array{
     *         totalContributions: int,
     *         weeks: array<int, array{
     *             contributionDays: array<int, array{
     *                 contributionCount: int,
     *                 date: string,
     *                 color: string,
     *                 weekday: int
     *             }>
     *         }>,
     *         months: array<int, array{
     *             name: string,
     *             year: int,
     *             firstDay: string,
     *             totalWeeks: int
     *         }>
     *     }|null,
     *     years: array<int, int>,
     *     selectedYear: int|null
     * }  $data
     */
    public function render(array $data, string $theme = 'dark', string $speed = 'normal'): string
    {
        $isDark = $theme !== 'light';

        // Theme Colors
        $bgColor = $isDark ? '#0d1117' : '#ffffff';
        $borderColor = $isDark ? '#30363d' : '#d0d7de';
        $textColor = $isDark ? '#8b949e' : '#57606a';
        $titleColor = $isDark ? '#c9d1d9' : '#24292f';
        $progressTrack = $isDark ? '#21262d' : '#e1e4e8';

        $palette = $isDark ? [
            0 => '#161b22',
            1 => '#0e4429',
            2 => '#006d32',
            3 => '#26a641',
            4 => '#39d353',
        ] : [
            0 => '#ebedf0',
            1 => '#9be9a8',
            2 => '#40c463',
            3 => '#30a14e',
            4 => '#216e39',
        ];

        $emptyColor = $palette[0];

        $calendar = $data['calendar'] ?? null;
        $totalContributions = $calendar['totalContributions'] ?? 0;
        $weeks = $calendar['weeks'] ?? [];

        $totalCols = 53;
        $cellWidth = 10;
        $cellGap = 3;
        $colPitch = $cellWidth + $cellGap; // 13px

        $startX = 36;
        $startY = 44;

        // Collect all active contribution cells
        $activeKeys = [];
        for ($col = 0; $col < $totalCols; $col++) {
            $weekDays = $weeks[$col]['contributionDays'] ?? [];
            for ($row = 0; $row < 7; $row++) {
                $day = $weekDays[$row] ?? null;
                if ($day && ($day['contributionCount'] ?? 0) > 0) {
                    $activeKeys["{$col},{$row}"] = true;
                }
            }
        }

        $totalActiveTargets = count($activeKeys);

        // Generate organic hunting route that targets and eats all active contribution blocks
        $username = $data['username'] ?? config('services.github.username', 'codebykenth');
        [$corners, $eatenAtStep, $totalDist] = $this->generateTargetHuntingRoute(
            $activeKeys,
            $totalCols,
            7,
            $username
        );

        $pathD = $this->buildSvgPath($corners, $startX, $startY, $colPitch);

        // Month Labels
        $monthsSvg = '';
        if (! empty($calendar['months'])) {
            $accumulatedWeeks = 0;
            foreach ($calendar['months'] as $m) {
                $mx = $startX + ($accumulatedWeeks * $colPitch);
                $escapedName = htmlspecialchars($m['name'], ENT_XML1, 'UTF-8');
                $monthsSvg .= "<text x=\"{$mx}\" y=\"34\" class=\"label\">{$escapedName}</text>";
                $accumulatedWeeks += $m['totalWeeks'];
            }
        } else {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $spacing = 4.4;
            foreach ($months as $i => $name) {
                $mx = (int) ($startX + ($i * $spacing * $colPitch));
                $monthsSvg .= "<text x=\"{$mx}\" y=\"34\" class=\"label\">{$name}</text>";
            }
        }

        // Pacing: relaxed comfortable speed (~7.5 steps per second)
        $durations = [
            'slow' => 54.0,
            'normal' => 42.0,
            'fast' => 28.0,
        ];
        $durationSec = $durations[$speed] ?? 42.0;
        $animationDuration = "{$durationSec}s";

        // Render Days grid with synchronized eating animations
        $cellsSvg = '';
        for ($col = 0; $col < $totalCols; $col++) {
            $weekDays = $weeks[$col]['contributionDays'] ?? [];
            for ($row = 0; $row < 7; $row++) {
                $day = $weekDays[$row] ?? null;
                $level = 0;

                if ($day) {
                    $count = $day['contributionCount'] ?? 0;
                    if ($count > 8) {
                        $level = 4;
                    } elseif ($count > 5) {
                        $level = 3;
                    } elseif ($count > 2) {
                        $level = 2;
                    } elseif ($count > 0) {
                        $level = 1;
                    }
                }

                $cx = $startX + ($col * $colPitch);
                $cy = $startY + ($row * $colPitch);
                $cellColor = $palette[$level];
                $cellKey = "{$col},{$row}";

                if ($level > 0 && isset($eatenAtStep[$cellKey])) {
                    $step = $eatenAtStep[$cellKey];
                    $f = round($step / $totalDist, 4);
                    if ($f >= 0.975) {
                        $f = 0.97;
                    }
                    $f2 = min(0.98, round($f + 0.003, 4));

                    if ($f === 0.0) {
                        $cellsSvg .= "<rect x=\"{$cx}\" y=\"{$cy}\" width=\"{$cellWidth}\" height=\"{$cellWidth}\" rx=\"2\" fill=\"{$emptyColor}\">"
                            ."<animate attributeName=\"fill\" dur=\"{$animationDuration}\" repeatCount=\"indefinite\" "
                            ."values=\"{$emptyColor};{$emptyColor};{$cellColor}\" keyTimes=\"0;0.985;1\" />"
                            .'</rect>';
                    } else {
                        $cellsSvg .= "<rect x=\"{$cx}\" y=\"{$cy}\" width=\"{$cellWidth}\" height=\"{$cellWidth}\" rx=\"2\" fill=\"{$cellColor}\">"
                            ."<animate attributeName=\"fill\" dur=\"{$animationDuration}\" repeatCount=\"indefinite\" "
                            ."values=\"{$cellColor};{$cellColor};{$emptyColor};{$emptyColor};{$cellColor}\" "
                            ."keyTimes=\"0;{$f};{$f2};0.985;1\" />"
                            .'</rect>';
                    }
                } else {
                    $cellsSvg .= "<rect x=\"{$cx}\" y=\"{$cy}\" width=\"{$cellWidth}\" height=\"{$cellWidth}\" rx=\"2\" fill=\"{$cellColor}\" />";
                }
            }
        }

        // Header Title
        $titleText = $totalContributions > 0
            ? number_format($totalContributions)." contributions by @{$username}"
            : "Contributions by @{$username}";
        $escapedTitle = htmlspecialchars($titleText, ENT_XML1, 'UTF-8');

        // Dynamic Score Counter & Progress Bar in Header
        $counterSvg = $this->buildDynamicCounter(
            $eatenAtStep,
            $totalActiveTargets,
            $totalDist,
            $animationDuration,
            $progressTrack
        );

        // Snake Timings (Head leading, Body segments lagging by 1 step each)
        $stepDur = round($durationSec / $totalDist, 4);
        $headBegin = -$durationSec;
        $seg1Begin = round($headBegin + (1 * $stepDur), 4);
        $seg2Begin = round($headBegin + (2 * $stepDur), 4);
        $seg3Begin = round($headBegin + (3 * $stepDur), 4);
        $seg4Begin = round($headBegin + (4 * $stepDur), 4);
        $seg5Begin = round($headBegin + (5 * $stepDur), 4);
        $seg6Begin = round($headBegin + (6 * $stepDur), 4);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 740 152" width="100%" height="152">
    <defs>
        <path id="snakePath" d="{$pathD}" fill="none" />
        <clipPath id="counterClip">
            <rect x="-32" y="-9" width="34" height="13" />
        </clipPath>
    </defs>

    <style>
        .card-bg { fill: {$bgColor}; stroke: {$borderColor}; stroke-width: 1; }
        .label { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size: 9px; fill: {$textColor}; }
        .title { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 600; fill: {$titleColor}; }
        .subtitle { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size: 9px; fill: #10b981; font-weight: 600; }
    </style>

    <!-- Card Background -->
    <rect width="100%" height="100%" rx="8" class="card-bg" />

    <!-- Header Title -->
    <text x="36" y="20" class="title">{$escapedTitle}</text>

    <!-- Dynamic Header Counter & Progress Bar -->
    {$counterSvg}

    <!-- Month Labels -->
    {$monthsSvg}

    <!-- Weekday Labels (Mon, Wed, Fri) -->
    <text x="30" y="66" text-anchor="end" class="label">Mon</text>
    <text x="30" y="92" text-anchor="end" class="label">Wed</text>
    <text x="30" y="118" text-anchor="end" class="label">Fri</text>

    <!-- Contribution Grid with Full-Run Eaten Animations -->
    <g id="contribution-cells">
        {$cellsSvg}
    </g>

    <!-- Snake Layer (Leading Head followed by Body Segments) -->
    <g id="snake">
        <!-- Body Segment 6 (Tail) -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg6Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.45" />
        </g>

        <!-- Body Segment 5 -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg5Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.55" />
        </g>

        <!-- Body Segment 4 -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg4Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.65" />
        </g>

        <!-- Body Segment 3 -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg3Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.75" />
        </g>

        <!-- Body Segment 2 -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg2Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.85" />
        </g>

        <!-- Body Segment 1 (Neck) -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$seg1Begin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <rect x="-4.5" y="-4.5" width="9" height="9" rx="2" fill="#34d399" opacity="0.95" />
        </g>

        <!-- Snake Head (Leading) -->
        <g>
            <animateMotion dur="{$animationDuration}" repeatCount="indefinite" rotate="auto" begin="{$headBegin}s">
                <mpath href="#snakePath" xlink:href="#snakePath" />
            </animateMotion>
            <!-- Red Tongue Facing Forward (+X direction) -->
            <polygon points="5,-1.5 9,0 5,1.5" fill="#ef4444" />

            <!-- Head Base -->
            <rect x="-5" y="-5" width="10" height="10" rx="3" fill="#10b981" stroke="#059669" stroke-width="1" />

            <!-- Eyes & Pupils Facing Forward -->
            <circle cx="2" cy="-2.5" r="1.3" fill="#ffffff" />
            <circle cx="2" cy="2.5" r="1.3" fill="#ffffff" />
            <circle cx="2.6" cy="-2.5" r="0.65" fill="#0f172a" />
            <circle cx="2.6" cy="2.5" r="0.65" fill="#0f172a" />
        </g>
    </g>
</svg>
SVG;
    }

    /**
     * Simulates greedy BFS target-hunting with momentum to create an organic tour of all active blocks.
     *
     * @param  array<string, bool>  $activeKeys
     * @return array{0: array<int, array{0: int, 1: int}>, 1: array<string, int>, 2: int}
     */
    protected function generateTargetHuntingRoute(
        array $activeKeys,
        int $cols,
        int $rows,
        string $seedStr
    ): array {
        if (empty($activeKeys)) {
            $defaultWaypoints = [
                [0, 1], [10, 1], [10, 3], [4, 3], [4, 5], [16, 5], [16, 2], [24, 2],
                [24, 0], [33, 0], [33, 3], [28, 3], [28, 6], [39, 6], [39, 4], [46, 4],
                [46, 1], [52, 1], [52, 5], [45, 5], [45, 3], [40, 3], [40, 6], [20, 6],
                [20, 4], [10, 4], [10, 6], [0, 6], [0, 1],
            ];
            $totalDist = 0;
            for ($i = 0; $i < count($defaultWaypoints) - 1; $i++) {
                [$c1, $r1] = $defaultWaypoints[$i];
                [$c2, $r2] = $defaultWaypoints[$i + 1];
                $totalDist += max(abs($c2 - $c1), abs($r2 - $r1));
            }

            return [$defaultWaypoints, [], max(1, $totalDist)];
        }

        $head = [0, 0];
        $uneaten = $activeKeys;
        $fullSteps = [$head];
        $eatenAtStep = [];
        $step = 0;

        if (isset($uneaten['0,0'])) {
            $eatenAtStep['0,0'] = 0;
            unset($uneaten['0,0']);
        }

        $lastDir = [1, 0];

        while (! empty($uneaten)) {
            $best = null;
            $bestScore = PHP_INT_MAX;
            $bestKey = null;

            foreach ($uneaten as $k => $_) {
                [$c, $r] = explode(',', $k);
                $c = (int) $c;
                $r = (int) $r;
                $manhattan = abs($head[0] - $c) + abs($head[1] - $r);

                // Directional momentum bonus
                $dot = ($c - $head[0]) * $lastDir[0] + ($r - $head[1]) * $lastDir[1];
                $score = $manhattan * 2 - ($dot > 0 ? 1 : 0);

                if ($score < $bestScore) {
                    $bestScore = $score;
                    $best = [$c, $r];
                    $bestKey = $k;
                }
            }

            if (! $best) {
                break;
            }

            $subPath = $this->bfsShortestPath($head, $best, $cols, $rows);
            if ($subPath === null || empty($subPath)) {
                unset($uneaten[$bestKey]);

                continue;
            }

            foreach ($subPath as $p) {
                $fullSteps[] = $p;
                $step++;
                $pKey = "{$p[0]},{$p[1]}";
                if (isset($uneaten[$pKey])) {
                    $eatenAtStep[$pKey] = $step;
                    unset($uneaten[$pKey]);
                }
            }

            $lastDir = [$best[0] - $head[0], $best[1] - $head[1]];
            $head = $best;
        }

        // Return path back to [0, 0] to close the loop
        $returnPath = $this->bfsShortestPath($head, [0, 0], $cols, $rows);
        if ($returnPath) {
            foreach ($returnPath as $p) {
                $fullSteps[] = $p;
                $step++;
            }
        }

        // Simplify collinear points into corner waypoints for compact SVG <path>
        $corners = [$fullSteps[0]];
        $countSteps = count($fullSteps);
        for ($i = 1; $i < $countSteps - 1; $i++) {
            $prev = $fullSteps[$i - 1];
            $curr = $fullSteps[$i];
            $next = $fullSteps[$i + 1];

            $d1 = [$curr[0] - $prev[0], $curr[1] - $prev[1]];
            $d2 = [$next[0] - $curr[0], $next[1] - $curr[1]];

            if ($d1 !== $d2) {
                $corners[] = $curr;
            }
        }
        $corners[] = end($fullSteps);

        return [$corners, $eatenAtStep, max(1, $step)];
    }

    /**
     * Breadth-first search for the shortest orthogonal path on the grid.
     *
     * @param  array{0: int, 1: int}  $start
     * @param  array{0: int, 1: int}  $target
     * @return array<int, array{0: int, 1: int}>|null
     */
    protected function bfsShortestPath(array $start, array $target, int $cols, int $rows): ?array
    {
        if ($start[0] === $target[0] && $start[1] === $target[1]) {
            return [];
        }

        $queue = [$start];
        $visited = ["{$start[0]},{$start[1]}" => true];
        $cameFrom = [];
        $found = false;
        $targetKey = "{$target[0]},{$target[1]}";

        while (! empty($queue)) {
            $curr = array_shift($queue);
            $currKey = "{$curr[0]},{$curr[1]}";

            if ($currKey === $targetKey) {
                $found = true;
                break;
            }

            $neighbors = [
                [$curr[0] + 1, $curr[1]],
                [$curr[0] - 1, $curr[1]],
                [$curr[0], $curr[1] + 1],
                [$curr[0], $curr[1] - 1],
            ];

            foreach ($neighbors as [$nc, $nr]) {
                if ($nc >= 0 && $nc < $cols && $nr >= 0 && $nr < $rows) {
                    $nKey = "{$nc},{$nr}";
                    if (! isset($visited[$nKey])) {
                        $visited[$nKey] = true;
                        $cameFrom[$nKey] = $curr;
                        $queue[] = [$nc, $nr];
                    }
                }
            }
        }

        if (! $found) {
            return null;
        }

        $path = [];
        $currKey = $targetKey;
        while (isset($cameFrom[$currKey])) {
            $prev = $cameFrom[$currKey];
            [$c, $r] = explode(',', $currKey);
            array_unshift($path, [(int) $c, (int) $r]);
            $currKey = "{$prev[0]},{$prev[1]}";
        }

        return $path;
    }

    /**
     * Builds the dynamic counter with a visible discrete number reel and a discrete-stepping progress bar.
     *
     * @param  array<string, int>  $activeEatenSteps
     */
    protected function buildDynamicCounter(
        array $activeEatenSteps,
        int $eatenTotal,
        int $totalDist,
        string $dur,
        string $progressTrack
    ): string {
        if ($eatenTotal === 0) {
            return '<text x="704" y="20" text-anchor="end" class="subtitle">&#9679; Playing</text>';
        }

        asort($activeEatenSteps);
        $sortedSteps = array_values($activeEatenSteps);

        $milestones = [0];
        $keyTimes = [0.0];
        $translations = ['0 0'];
        $progressWidths = [0];

        $barMax = 50;
        $lastK = 0.0;

        for ($i = 0; $i < $eatenTotal; $i++) {
            $num = $i + 1;
            $step = $sortedSteps[$i];
            $k = round($step / $totalDist, 4);
            if ($k <= $lastK) {
                $k = min(0.984, round($lastK + 0.0001, 5));
            }
            if ($k >= 0.985) {
                $k = 0.984;
            }

            $milestones[] = $num;
            $keyTimes[] = $k;
            $translations[] = '0 -'.($num * 13);
            $progressWidths[] = (int) round(($num / $eatenTotal) * $barMax);
            $lastK = $k;
        }

        // Hold at 100% until 0.985
        $keyTimes[] = 0.985;
        $translations[] = '0 -'.($eatenTotal * 13);
        $progressWidths[] = $barMax;

        // Reset to 0 at end of loop
        $keyTimes[] = 1.0;
        $translations[] = '0 0';
        $progressWidths[] = 0;

        // Ensure keyTimes are strictly non-decreasing
        for ($i = 1; $i < count($keyTimes); $i++) {
            if ($keyTimes[$i] < $keyTimes[$i - 1]) {
                $keyTimes[$i] = $keyTimes[$i - 1];
            }
        }

        $transValuesStr = implode('; ', $translations);
        $keyTimesStr = implode('; ', $keyTimes);
        $barValuesStr = implode(';', $progressWidths);

        // Text reel: numbers end at x=0, perfectly inside the clip rectangle [-32, 2]
        $textReelSvg = '';
        foreach ($milestones as $idx => $val) {
            $ty = $idx * 13;
            $textReelSvg .= "<text x=\"0\" y=\"{$ty}\" text-anchor=\"end\">{$val}</text>";
        }

        return <<<SVG
    <g transform="translate(565, 20)" class="subtitle">
        <text x="0" y="0">&#9679; Eaten:</text>

        <!-- Discrete Number Reel (Fully Visible in [-32, 2]) -->
        <g transform="translate(48, 0)">
            <g clip-path="url(#counterClip)">
                <g>
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        dur="{$dur}"
                        repeatCount="indefinite"
                        calcMode="discrete"
                        values="{$transValuesStr}"
                        keyTimes="{$keyTimesStr}"
                    />
                    {$textReelSvg}
                </g>
            </g>
        </g>

        <!-- Total active blocks target -->
        <text x="52" y="0">/ {$eatenTotal}</text>

        <!-- Discrete Progress Bar (steps forward only as blocks are eaten) -->
        <rect x="88" y="-7" width="{$barMax}" height="6" rx="3" fill="{$progressTrack}" />
        <rect x="88" y="-7" width="0" height="6" rx="3" fill="#10b981">
            <animate attributeName="width" dur="{$dur}" repeatCount="indefinite" calcMode="discrete" values="{$barValuesStr}" keyTimes="{$keyTimesStr}" />
        </rect>
    </g>
SVG;
    }

    /**
     * Converts grid corners into pixel path coordinates.
     *
     * @param  array<int, array{0: int, 1: int}>  $corners
     */
    protected function buildSvgPath(array $corners, int $startX, int $startY, int $pitch): string
    {
        $points = [];
        foreach ($corners as [$c, $r]) {
            $px = $startX + ($c * $pitch) + 5;
            $py = $startY + ($r * $pitch) + 5;
            $points[] = "{$px} {$py}";
        }

        return 'M '.implode(' L ', $points).' Z';
    }
}
