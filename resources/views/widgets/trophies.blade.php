@php
    $fontFamily = 'Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji';
    $frameColor = $theme['border'] ?? '#e1e4e8';
    $background = $theme['bg'] ?? '#ffffff';
    $titleColor = $theme['title'] ?? '#000000';
    $textColor = $theme['text'] ?? '#666666';
    $barColor = $theme['accent'] ?? '#0366d6';
    $barTrackOpacity = 0.3;
    $barMaxWidth = 80;
    $barX = 15;
    $barY = 101;

    $rankPalette = function (string $rank): array {
        $first = substr($rank, 0, 1);
        if ($first === 'S') {
            return ['base' => '#FAD200', 'shadow' => '#C8A090', 'text' => '#886000', 'laurel' => true];
        }
        if ($first === 'A') {
            return ['base' => '#B0B0B0', 'shadow' => '#9090C0', 'text' => '#505050', 'laurel' => true];
        }
        if ($rank === 'B') {
            return ['base' => '#A18D66', 'shadow' => '#816D96', 'text' => '#412D06', 'laurel' => false];
        }
        return ['base' => '#777777', 'shadow' => '#333333', 'text' => '#333333', 'laurel' => false];
    };

    $cupPaths = '<path d="M7 10h2v4H7v-4z"/><path d="M10 11c0 .552-.895 1-2 1s-2-.448-2-1 .895-1 2-1 2 .448 2 1z"/><path fill-rule="evenodd" d="M12.5 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-3 2a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm-6-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-3 2a3 3 0 1 1 6 0 3 3 0 0 1-6 0z"/><path d="M3 1h10c-.495 3.467-.5 10-5 10S3.495 4.467 3 1zm0 15a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1H3zm2-1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1H5z"/>';
@endphp
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $cardW }}" height="{{ $cardH }}" viewBox="0 0 {{ $cardW }} {{ $cardH }}" fill="none">
@foreach ($trophies as $index => $trophy)
@php
    $palette = $rankPalette($trophy['rank']);
    $rankLetter = substr($trophy['rank'], 0, 1);
    $extraIcons = max(0, strlen($trophy['rank']) - 1);
    $gradId = 'trophy-grad-' . $index . '-' . preg_replace('/[^A-Za-z0-9]/', '', $trophy['rank']);
    $barWidth = round($barMaxWidth * max(0, min(1, (float) ($trophy['progress'] ?? 0))), 1);
    $topMessage = mb_strimwidth($trophy['topMessage'], 0, 22, '…');
    $title = mb_strimwidth($trophy['title'], 0, 18, '…');
@endphp
    <svg x="{{ $trophy['x'] }}" y="{{ $trophy['y'] }}" width="{{ $panel }}" height="{{ $panel }}" viewBox="0 0 {{ $panel }} {{ $panel }}" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0.5" y="0.5" rx="4.5" width="{{ $panel - 1 }}" height="{{ $panel - 1 }}" stroke="{{ $frameColor }}" fill="{{ $background }}" stroke-opacity="{{ $noFrame ? '0' : '1' }}" fill-opacity="{{ $noBg ? '0' : '1' }}"/>
        @if ($palette['laurel'])
        <g opacity="0.9" fill="none" stroke="#009366" stroke-width="1.6" stroke-linecap="round">
            <path d="M22 62 Q14 48 22 32 Q24 28 27 25"/>
            <path d="M88 62 Q96 48 88 32 Q86 28 83 25"/>
            <g fill="#009366" stroke="none" opacity="0.85">
                <ellipse cx="20" cy="52" rx="2.4" ry="1.4" transform="rotate(-40 20 52)"/>
                <ellipse cx="19" cy="44" rx="2.4" ry="1.4" transform="rotate(-55 19 44)"/>
                <ellipse cx="21" cy="36" rx="2.2" ry="1.3" transform="rotate(-70 21 36)"/>
                <ellipse cx="90" cy="52" rx="2.4" ry="1.4" transform="rotate(40 90 52)"/>
                <ellipse cx="91" cy="44" rx="2.4" ry="1.4" transform="rotate(55 91 44)"/>
                <ellipse cx="89" cy="36" rx="2.2" ry="1.3" transform="rotate(70 89 36)"/>
            </g>
        </g>
        @endif
        @if ($extraIcons >= 2)
        <svg x="7" y="35" width="65" height="65" viewBox="0 0 30 30" fill="{{ $palette['base'] }}" xmlns="http://www.w3.org/2000/svg">{!! $cupPaths !!}</svg>
        @endif
        @if ($extraIcons >= 1)
        <svg x="68" y="35" width="65" height="65" viewBox="0 0 30 30" fill="{{ $palette['base'] }}" xmlns="http://www.w3.org/2000/svg">{!! $cupPaths !!}</svg>
        @endif
        <defs>
            <linearGradient id="{{ $gradId }}" gradientTransform="rotate(45)">
                <stop offset="0%" stop-color="{{ $palette['base'] }}"/>
                <stop offset="70%" stop-color="{{ $palette['base'] }}"/>
                <stop offset="100%" stop-color="{{ $palette['shadow'] }}"/>
            </linearGradient>
        </defs>
        <svg x="28" y="20" width="100" height="100" viewBox="0 0 30 30" fill="url(#{{ $gradId }})" xmlns="http://www.w3.org/2000/svg">
            {!! $cupPaths !!}
            <circle cx="8" cy="6" r="4" fill="#ffffff"/>
            <text x="6" y="8" font-family="Courier, Monospace" font-size="7" fill="{{ $palette['text'] }}">{{ $rankLetter }}</text>
        </svg>
        <text x="50%" y="18" text-anchor="middle" font-family="{{ $fontFamily }}" font-weight="bold" font-size="13" fill="{{ $titleColor }}">{{ $title }}</text>
        <text x="50%" y="85" text-anchor="middle" font-family="{{ $fontFamily }}" font-weight="bold" font-size="10.5" fill="{{ $textColor }}">{{ $topMessage }}</text>
        <text x="50%" y="97" text-anchor="middle" font-family="{{ $fontFamily }}" font-weight="bold" font-size="10" fill="{{ $textColor }}">{{ $trophy['bottomMessage'] }}</text>
        <rect x="{{ $barX }}" y="{{ $barY }}" rx="1" width="{{ $barMaxWidth }}" height="3.2" opacity="{{ $barTrackOpacity }}" fill="{{ $barColor }}"/>
        <rect x="{{ $barX }}" y="{{ $barY }}" rx="1" width="{{ $barWidth }}" height="3.2" fill="{{ $barColor }}"/>
    </svg>
@endforeach
</svg>
