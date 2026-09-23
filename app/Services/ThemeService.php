<?php

namespace App\Services;

class ThemeService
{
    /**
     * Available themes with their color palettes.
     *
     * @return array<string, array{bg: string, border: string, title: string, text: string, icon: string, accent: string, ring: string}>
     */
    public static function all(): array
    {
        return [
            'light' => [
                'bg' => '#ffffff',
                'border' => '#e4e2e2',
                'title' => '#2f80ed',
                'text' => '#434d58',
                'icon' => '#4c71f2',
                'accent' => '#2f80ed',
                'ring' => '#e4e2e2',
            ],
            'dark' => [
                'bg' => '#0d1117',
                'border' => '#30363d',
                'title' => '#58a6ff',
                'text' => '#c9d1d9',
                'icon' => '#58a6ff',
                'accent' => '#58a6ff',
                'ring' => '#30363d',
            ],
            'radical' => [
                'bg' => '#141321',
                'border' => '#fe428e',
                'title' => '#fe428e',
                'text' => '#a9fef7',
                'icon' => '#f8d847',
                'accent' => '#fe428e',
                'ring' => '#fe428e',
            ],
            'dracula' => [
                'bg' => '#282a36',
                'border' => '#6272a4',
                'title' => '#ff79c6',
                'text' => '#f8f8f2',
                'icon' => '#bd93f9',
                'accent' => '#ff79c6',
                'ring' => '#6272a4',
            ],
            'tokyonight' => [
                'bg' => '#1a1b27',
                'border' => '#545c7e',
                'title' => '#70a5fd',
                'text' => '#38bdae',
                'icon' => '#bf91f3',
                'accent' => '#70a5fd',
                'ring' => '#545c7e',
            ],
            'gruvbox' => [
                'bg' => '#282828',
                'border' => '#504945',
                'title' => '#fabd2f',
                'text' => '#ebdbb2',
                'icon' => '#fe8019',
                'accent' => '#fabd2f',
                'ring' => '#504945',
            ],
            'nord' => [
                'bg' => '#2e3440',
                'border' => '#4c566a',
                'title' => '#81a1c1',
                'text' => '#d8dee9',
                'icon' => '#88c0d0',
                'accent' => '#81a1c1',
                'ring' => '#4c566a',
            ],
            'catppuccin' => [
                'bg' => '#1e1e2e',
                'border' => '#585b70',
                'title' => '#cba6f7',
                'text' => '#cdd6f4',
                'icon' => '#f5c2e7',
                'accent' => '#cba6f7',
                'ring' => '#585b70',
            ],
            'onedark' => [
                'bg' => '#282c34',
                'border' => '#4b5263',
                'title' => '#e4bf7a',
                'text' => '#abb2bf',
                'icon' => '#61afef',
                'accent' => '#e4bf7a',
                'ring' => '#4b5263',
            ],
            'solarized' => [
                'bg' => '#002b36',
                'border' => '#586e75',
                'title' => '#268bd2',
                'text' => '#839496',
                'icon' => '#b58900',
                'accent' => '#268bd2',
                'ring' => '#586e75',
            ],
            'rose_pine' => [
                'bg' => '#191724',
                'border' => '#403d52',
                'title' => '#ebbcba',
                'text' => '#e0def4',
                'icon' => '#c4a7e7',
                'accent' => '#ebbcba',
                'ring' => '#403d52',
            ],
            'ayu' => [
                'bg' => '#0b0e14',
                'border' => '#1c2433',
                'title' => '#e6b450',
                'text' => '#c7c7c7',
                'icon' => '#ffb454',
                'accent' => '#e6b450',
                'ring' => '#1c2433',
            ],
            'github_dark' => [
                'bg' => '#0d1117',
                'border' => '#30363d',
                'title' => '#58a6ff',
                'text' => '#c9d1d9',
                'icon' => '#58a6ff',
                'accent' => '#58a6ff',
                'ring' => '#30363d',
            ],
            'discord' => [
                'bg' => '#23272a',
                'border' => '#7289da',
                'title' => '#7289da',
                'text' => '#ffffff',
                'icon' => '#7289da',
                'accent' => '#7289da',
                'ring' => '#7289da',
            ],
            'matrix' => [
                'bg' => '#000000',
                'border' => '#00ff00',
                'title' => '#00ff00',
                'text' => '#00ff00',
                'icon' => '#00ff00',
                'accent' => '#00ff00',
                'ring' => '#00ff00',
            ],
            'synthwave' => [
                'bg' => '#2b213a',
                'border' => '#e2e9ec',
                'title' => '#e2e9ec',
                'text' => '#e5289e',
                'icon' => '#ef8539',
                'accent' => '#e5289e',
                'ring' => '#e2e9ec',
            ],
            'vue-dark' => [
                'bg' => '#273849',
                'border' => '#41b883',
                'title' => '#41b883',
                'text' => '#ffffff',
                'icon' => '#41b883',
                'accent' => '#41b883',
                'ring' => '#41b883',
            ],
        ];
    }

    /**
     * Get a specific theme's colors, defaulting to 'dark'.
     *
     * @return array{bg: string, border: string, title: string, text: string, icon: string, accent: string, ring: string}
     */
    public static function get(string $theme = 'dark'): array
    {
        $themes = self::all();

        return $themes[$theme] ?? $themes['dark'];
    }

    /**
     * Get list of available theme names.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_keys(self::all());
    }
}
