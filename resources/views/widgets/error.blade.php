@php
    // Error Widget: Renders an inline SVG error card when GITHUB_TOKEN is missing.
    // Used by WidgetController::renderWidget() on configuration errors.
    $title = $title ?? 'Error';
    $message = $message ?? 'Something went wrong.';
    $hint = $hint ?? '';
@endphp
@include('components.widget-error', ['title' => $title, 'message' => $message, 'hint' => $hint])
