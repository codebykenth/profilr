@php
    // Extract props with sensible defaults for the SVG card wrapper.
    // $theme is the theme array from ThemeService::get().
    // $width and $height define the SVG viewport dimensions.
    $theme = $theme ?? [];
    $width = $width ?? 495;
    $height = $height ?? 220;
@endphp
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 {{ $width }} {{ $height }}" fill="none">
    <rect x="0.5" y="0.5" rx="8" width="{{ $width - 1 }}" height="{{ $height - 1 }}" fill="{{ $theme['bg'] }}" stroke="{{ $theme['border'] }}" stroke-opacity="1"/>
