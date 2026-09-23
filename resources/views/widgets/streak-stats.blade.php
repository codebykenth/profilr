<svg xmlns="http://www.w3.org/2000/svg" width="495" height="195" viewBox="0 0 495 195" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="494" height="194" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    <g transform="translate(25, 35)">
        <text class="header">🔥 Contribution Streak</text>
    </g>

    {{-- Three columns: Current Streak | Longest Streak | Total Contributions --}}
    <g transform="translate(0, 65)">
        {{-- Current Streak --}}
        <g transform="translate(82, 0)">
            <text class="stat-value" text-anchor="middle" y="30">{{ $streak['currentStreak'] }}</text>
            <text class="stat-label" text-anchor="middle" y="52">Current Streak</text>
            <text class="stat-date" text-anchor="middle" y="68">{{ $streak['currentRange'] }}</text>
        </g>

        <line class="divider" x1="165" y1="5" x2="165" y2="90" />

        {{-- Longest Streak --}}
        <g transform="translate(247, 0)">
            <text class="stat-value" text-anchor="middle" y="30">{{ $streak['longestStreak'] }}</text>
            <text class="stat-label" text-anchor="middle" y="52">Longest Streak</text>
            <text class="stat-date" text-anchor="middle" y="68">{{ $streak['longestRange'] }}</text>
        </g>

        <line class="divider" x1="330" y1="5" x2="330" y2="90" />

        {{-- Total --}}
        <g transform="translate(412, 0)">
            <text class="stat-value" text-anchor="middle" y="30">{{ $streak['currentYearContributions'] }}</text>
            <text class="stat-label" text-anchor="middle" y="52">Total (This Year)</text>
        </g>
    </g>
</svg>
