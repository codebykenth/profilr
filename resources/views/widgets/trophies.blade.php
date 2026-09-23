<svg xmlns="http://www.w3.org/2000/svg" width="495" height="250" viewBox="0 0 495 250" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="494" height="249" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    <g transform="translate(25, 35)">
        <text class="header">{{ $username }}'s GitHub Trophies</text>
    </g>

    <g transform="translate(25, 60)">
        @foreach ($trophies as $trophy)
            <g transform="translate({{ $trophy['x'] }}, {{ $trophy['y'] }})">
                <rect width="{{ $tileW }}" height="80" rx="6" fill="{{ $theme['ring'] }}" opacity="0.45"/>

                <svg class="icon" viewBox="0 0 16 16" width="20" height="20" x="14" y="16">
                    <path d="{{ $trophy['icon'] }}"/>
                </svg>

                <circle cx="196" cy="20" r="10" fill="{{ $trophy['rankColor'] }}"/>
                <text class="trophy-rank" x="196" y="24" text-anchor="middle" fill="#ffffff">{{ $trophy['rank'] }}</text>

                <text class="trophy-title" x="14" y="58">{{ $trophy['title'] }}</text>
                <text class="trophy-value" x="{{ $tileW - 14 }}" y="58" text-anchor="end">{{ $trophy['value'] }}</text>
            </g>
        @endforeach
    </g>
</svg>