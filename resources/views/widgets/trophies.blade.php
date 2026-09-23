<svg xmlns="http://www.w3.org/2000/svg" width="{{ $cardW }}" height="{{ $cardH }}" viewBox="0 0 {{ $cardW }} {{ $cardH }}" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="{{ $cardW - 1 }}" height="{{ $cardH - 1 }}" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    <g transform="translate({{ $padX }}, 36)">
        <text class="header">{{ $username }}'s GitHub Trophies</text>
    </g>

    <g transform="translate({{ $padX }}, {{ $padY }})">
        @foreach ($trophies as $trophy)
            <g transform="translate({{ $trophy['x'] }}, {{ $trophy['y'] }})">
                <rect width="{{ $tileW }}" height="{{ $tileH }}" rx="8" fill="{{ $theme['ring'] }}" fill-opacity="0.35" stroke="{{ $theme['border'] }}" stroke-opacity="0.8"/>

                <rect x="12" y="14" width="36" height="36" rx="8" fill="{{ $theme['bg'] }}" fill-opacity="0.65"/>
                <svg class="icon" viewBox="0 0 16 16" width="22" height="22" x="19" y="21">
                    <path d="{{ $trophy['icon'] }}"/>
                </svg>

                <circle cx="{{ $tileW - 24 }}" cy="24" r="12" fill="{{ $trophy['rankColor'] }}"/>
                <text class="trophy-rank" x="{{ $tileW - 24 }}" y="28" text-anchor="middle" fill="#0d1117">{{ $trophy['rank'] }}</text>

                <text class="trophy-title" x="14" y="64">{{ $trophy['title'] }}</text>
                <text class="trophy-value" x="{{ $tileW - 14 }}" y="64" text-anchor="end">{{ $trophy['value'] }}</text>
            </g>
        @endforeach
    </g>
</svg>
