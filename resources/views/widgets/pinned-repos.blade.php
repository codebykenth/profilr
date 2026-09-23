@include('components.widget-card', ['theme' => $theme, 'width' => $svgWidth, 'height' => $svgHeight])
    <g transform="translate(25, 35)">
        <text class="header">📌 Pinned Repositories</text>
    </g>

    <g transform="translate(25, 55)">
        @foreach ($repos as $repo)
            <g class="repo-card" style="animation-delay: {{ $repo['delay'] }}ms" transform="translate({{ $repo['x'] }}, {{ $repo['y'] }})">
                @include('components.svg-icons', ['name' => 'repo', 'theme' => $theme])
                <text class="repo-name" x="32" y="24">{{ $repo['name'] }}</text>

                <text class="repo-desc" x="12" y="50">{{ $repo['description'] }}</text>

                {{-- Language indicator, stars, and forks --}}
                <g transform="translate(12, {{ $cardH - 20 }})">
                    @if ($repo['language'])
                        <circle cx="5" cy="-3" r="5" fill="{{ $repo['languageColor'] }}"/>
                        <text class="repo-meta" x="14" y="0">{{ $repo['language'] }}</text>
                    @endif

                    <g transform="translate(110, 0)">
                        @include('components.svg-icons', ['name' => 'star'])
                        <text class="repo-meta" x="16" y="0">{{ $repo['stars'] }}</text>
                    </g>

                    <g transform="translate(160, 0)">
                        @include('components.svg-icons', ['name' => 'repo'])
                        <text class="repo-meta" x="16" y="0">{{ $repo['forks'] }}</text>
                    </g>
                </g>
            </g>
        @endforeach
    </g>
</svg>
