<svg xmlns="http://www.w3.org/2000/svg" width="{{ $svgWidth }}" height="{{ $svgHeight }}" viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="{{ $svgWidth - 1 }}" height="{{ $svgHeight - 1 }}" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    <g transform="translate(25, 35)">
        <text class="header">📌 Pinned Repositories</text>
    </g>

    <g transform="translate(25, 55)">
        @foreach ($repos as $repo)
            <g class="repo-card" style="animation-delay: {{ $repo['delay'] }}ms" transform="translate({{ $repo['x'] }}, {{ $repo['y'] }})">
                <rect x="0" y="0" rx="6" width="{{ $cardW }}" height="{{ $cardH }}" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}" stroke-opacity="0.6"/>

                {{-- Repo icon --}}
                <svg viewBox="0 0 16 16" width="14" height="14" x="12" y="14" fill="{{ $theme['icon'] }}">
                    <path d="M2 2.5A2.5 2.5 0 014.5 0h8.75a.75.75 0 01.75.75v12.5a.75.75 0 01-.75.75h-2.5a.75.75 0 110-1.5h1.75v-2h-8a1 1 0 00-.714 1.7.75.75 0 01-1.072 1.05A2.495 2.495 0 012 11.5v-9zm10.5-1h-8a1 1 0 00-1 1v6.708A2.486 2.486 0 014.5 9h8V1.5zm-8 11h8v-1.5h-8a1 1 0 000 1.5z"/>
                </svg>
                <text class="repo-name" x="32" y="24">{{ $repo['name'] }}</text>

                <text class="repo-desc" x="12" y="50">{{ $repo['description'] }}</text>

                {{-- Language & Stars & Forks --}}
                <g transform="translate(12, {{ $cardH - 20 }})">
                    @if ($repo['language'])
                        <circle cx="5" cy="-3" r="5" fill="{{ $repo['languageColor'] }}"/>
                        <text class="repo-meta" x="14" y="0">{{ $repo['language'] }}</text>
                    @endif

                    <g transform="translate(110, 0)">
                        <svg viewBox="0 0 16 16" width="12" height="12" y="-10" fill="{{ $theme['text'] }}" opacity="0.5">
                            <path d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25z"/>
                        </svg>
                        <text class="repo-meta" x="16" y="0">{{ $repo['stars'] }}</text>
                    </g>

                    <g transform="translate(160, 0)">
                        <svg viewBox="0 0 16 16" width="12" height="12" y="-10" fill="{{ $theme['text'] }}" opacity="0.5">
                            <path d="M5 3.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm0 2.122a2.25 2.25 0 10-1.5 0v.878A2.25 2.25 0 005.75 8.5h1.5v2.128a2.251 2.251 0 101.5 0V8.5h1.5a2.25 2.25 0 002.25-2.25v-.878a2.25 2.25 0 10-1.5 0v.878a.75.75 0 01-.75.75h-4.5A.75.75 0 015 6.25v-.878zm3.75 7.378a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm3-8.75a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                        </svg>
                        <text class="repo-meta" x="16" y="0">{{ $repo['forks'] }}</text>
                    </g>
                </g>
            </g>
        @endforeach
    </g>
</svg>
