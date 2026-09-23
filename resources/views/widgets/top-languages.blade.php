@include('components.widget-card', ['theme' => $theme, 'width' => 495, 'height' => $cardHeight])
    <g transform="translate(25, 35)">
        <text class="header">Most Used Languages</text>
    </g>

    {{-- Language progress bar with mask for clipping --}}
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

    {{-- Language list rendered as rows with colored circles --}}
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
