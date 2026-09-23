<?php

return [

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'graphql_url' => 'https://api.github.com/graphql',
        'cache_ttl' => env('GITHUB_CACHE_TTL', 1800), // 30 minutes
        'enable_api' => env('ENABLE_API', false),
        'enable_ui' => env('ENABLE_UI', false),
        'ui_url' => env('UI_URL'),
        'repo_url' => env('REPO_URL', 'https://github.com/codebykenth/profilr'),
    ],

];
