<svg xmlns="http://www.w3.org/2000/svg" width="495" height="{{ $cardHeight }}" viewBox="0 0 495 {{ $cardHeight }}" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="494" height="{{ $cardHeight - 1 }}" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    <g transform="translate(25, 35)">
        <text class="header">Most Used Languages</text>
    </g>

    {{-- Language progress bar --}}
    <g transform="translate(22, 55)">
        <mask id="bar-mask">
            <rect x="0" y="0" width="{{ $barWidth }}" height="8" rx="4" fill="white"/>
        </mask>
        <rect x="0" y="0" width="{{ $barWidth }}" height="8" rx="4" fill="{{ $theme['border'] }}"/>
        <g mask="url(#bar-mask)">
            @foreach ($barItems as $item)
                <rect class="bar" style="animation-delay: {{ $item['delay'] }}ms" x="{{ $item['x'] }}" y="0" width="{{ $item['width'] }}" height="8" fill="{{ $item['color'] }}"/>
            @endforeach
        </g>
    </g>

    {{-- Language list --}}
    <g transform="translate(22, 80)">
        @foreach ($listItems as $item)
            <g class="lang-row" style="animation-delay: {{ $item['delay'] }}ms" transform="translate({{ $item['x'] }}, {{ $item['y'] }})">
                <circle cx="7" cy="8" r="6" fill="{{ $item['color'] }}"/>
                <text class="lang-name" x="20" y="13">{{ $item['name'] }}</text>
                <text class="lang-pct" x="200" y="13" text-anchor="end">{{ $item['percentage'] }}%</text>
            </g>
        @endforeach
    </g>
</svg>
