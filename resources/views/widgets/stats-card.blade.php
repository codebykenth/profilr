@php
    // Stats Card Widget: Displays total stars, commits, PRs, issues, and contributions.
    // Receives $theme from ThemeService::get(), $stats from WidgetController::stats().
    $name = $profile['name'] ?: $profile['login'];
@endphp
@include('components.widget-card', ['theme' => $theme, 'width' => 495, 'height' => 220])
    <g transform="translate(25, 38)">
        <text class="header" data-testid="header">{{ $name }}'s GitHub Stats</text>
    </g>

    <g transform="translate(25, 78)">
        {{-- Stars --}}
        <g class="row" style="animation-delay: 0ms" transform="translate(0, 0)">
            @include('components.svg-icons', ['name' => 'star'])
            <text class="stat-label" x="28" y="0">Total Stars Earned:</text>
            <text class="stat-value" x="445" y="0" text-anchor="end">{{ $stats['totalStars'] }}</text>
        </g>

        {{-- Commits --}}
        <g class="row" style="animation-delay: 100ms" transform="translate(0, 28)">
            @include('components.svg-icons', ['name' => 'commit'])
            <text class="stat-label" x="28" y="0">Total Commits:</text>
            <text class="stat-value" x="445" y="0" text-anchor="end">{{ $stats['totalCommits'] }}</text>
        </g>

        {{-- PRs --}}
        <g class="row" style="animation-delay: 200ms" transform="translate(0, 56)">
            @include('components.svg-icons', ['name' => 'pr'])
            <text class="stat-label" x="28" y="0">Total PRs:</text>
            <text class="stat-value" x="445" y="0" text-anchor="end">{{ $stats['totalPRs'] }}</text>
        </g>

        {{-- Issues --}}
        <g class="row" style="animation-delay: 300ms" transform="translate(0, 84)">
            @include('components.svg-icons', ['name' => 'issue'])
            <text class="stat-label" x="28" y="0">Total Issues:</text>
            <text class="stat-value" x="445" y="0" text-anchor="end">{{ $stats['totalIssues'] }}</text>
        </g>

        {{-- Contributions --}}
        <g class="row" style="animation-delay: 400ms" transform="translate(0, 112)">
            @include('components.svg-icons', ['name' => 'contribution'])
            <text class="stat-label" x="28" y="0">Contributed to (last year):</text>
            <text class="stat-value" x="445" y="0" text-anchor="end">{{ $stats['totalContributions'] }}</text>
        </g>
    </g>
</svg>
