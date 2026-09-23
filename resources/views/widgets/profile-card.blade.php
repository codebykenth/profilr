<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="495" height="195" viewBox="0 0 495 195" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="494" height="194" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}"/>

    {{-- Avatar circle --}}
    <g transform="translate(25, 25)">
        <defs>
            <clipPath id="avatar-clip">
                <circle cx="40" cy="40" r="40"/>
            </clipPath>
        </defs>
        <circle cx="40" cy="40" r="42" fill="{{ $theme['accent'] }}" opacity="0.2"/>
        <circle cx="40" cy="40" r="40" fill="{{ $theme['border'] }}"/>
        @if (!empty($profile['avatarUrl']))
            <image xlink:href="{{ $profile['avatarUrl'] }}" x="0" y="0" width="80" height="80" clip-path="url(#avatar-clip)" preserveAspectRatio="xMidYMid slice"/>
        @endif
    </g>

    {{-- Name & Bio --}}
    <g transform="translate(120, 35)">
        <text class="name animate" style="animation-delay: 0ms">{{ $profile['displayName'] }}</text>
        <text class="username animate" style="animation-delay: 100ms" y="22">{{ '@' . $profile['login'] }}</text>
        <text class="bio animate" style="animation-delay: 200ms" y="45">{{ $profile['bio'] }}</text>
    </g>

    {{-- Stats row --}}
    <g transform="translate(120, 130)">
        <g class="animate" style="animation-delay: 300ms">
            <text class="meta-value">{{ $profile['followers'] }}</text>
            <text class="meta-label" y="16">followers</text>
        </g>

        <g class="animate" style="animation-delay: 400ms" transform="translate(90, 0)">
            <text class="meta-value">{{ $profile['following'] }}</text>
            <text class="meta-label" y="16">following</text>
        </g>

        <g class="animate" style="animation-delay: 500ms" transform="translate(180, 0)">
            <text class="meta-value">{{ $profile['repositories'] }}</text>
            <text class="meta-label" y="16">repos</text>
        </g>

        @if (!empty($profile['memberSince']))
            <g class="animate" style="animation-delay: 600ms" transform="translate(260, 0)">
                <text class="meta-label" y="8">Member since {{ $profile['memberSince'] }}</text>
            </g>
        @endif
    </g>
</svg>
