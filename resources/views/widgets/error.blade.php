<svg xmlns="http://www.w3.org/2000/svg" width="495" height="120" viewBox="0 0 495 120" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="494" height="119" fill="#1a1a2e" stroke="#2a2a40"/>
    <text class="title" x="25" y="40">⚠️ {{ $title ?? 'Error' }}</text>
    <text class="msg" x="25" y="65">{{ $message ?? 'Something went wrong.' }}</text>
    <text class="msg" x="25" y="85" opacity="0.5">{{ $hint ?? '' }}</text>
</svg>
