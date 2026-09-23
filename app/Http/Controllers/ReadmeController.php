<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use App\Services\SkillService;
use App\Services\ThemeService;
use Illuminate\Http\Request;

class ReadmeController extends Controller
{
    public function __construct(
        private GitHubService $github,
    ) {}

    /**
     * GET / — Product landing page showcasing features and benefits.
     */
    public function landing(Request $request)
    {
        $hasToken = ! empty(config('services.github.token'));
        $repoUrl = config('services.github.repo_url', 'https://github.com/codebykenth/github-readme-generator');
        $defaultUsername = $request->query('username', '');

        if (! $defaultUsername && $hasToken) {
            try {
                $defaultUsername = $this->github->getAuthenticatedUsername() ?? '';
            } catch (\Throwable) {
                $defaultUsername = '';
            }
        }

        return view('landing', [
            'hasToken' => $hasToken,
            'repoUrl' => $repoUrl,
            'defaultUsername' => $defaultUsername,
            'featuredSkills' => [
                'php',
                'laravel',
                'react',
                'typescript',
                'tailwind',
                'vue',
                'nodejs',
                'python',
                'docker',
                'mysql',
                'postgresql',
                'aws',
                'git',
                'github',
                'redis',
                'nextjs',
                'graphql',
                'figma',
            ],
        ]);
    }

    /**
     * GET /builder — Split-screen interactive profile builder workspace.
     */
    public function builder(Request $request)
    {
        $themes = ThemeService::names();
        $baseUrl = rtrim(config('app.url') ?: $request->getSchemeAndHttpHost(), '/');
        $hasToken = ! empty(config('services.github.token'));
        $repoUrl = config('services.github.repo_url', 'https://github.com/codebykenth/github-readme-generator');

        $defaultUsername = $request->query('username', '');
        if (! $defaultUsername && $hasToken) {
            try {
                $defaultUsername = $this->github->getAuthenticatedUsername() ?? '';
            } catch (\Throwable) {
                $defaultUsername = '';
            }
        }

        return view('builder', [
            'themes' => $themes,
            'themeColors' => ThemeService::all(),
            'baseUrl' => $baseUrl,
            'hasToken' => $hasToken,
            'apiEnabled' => config('services.github.enable_api', false),
            'repoUrl' => $repoUrl,
            'defaultUsername' => $defaultUsername,
            'skillCategories' => SkillService::categorized(),
        ]);
    }

    /**
     * GET /api/readme — Generate a complete README.md template as raw Markdown.
     */
    public function generate(Request $request)
    {
        $username = $request->query('username');
        $theme = $request->query('theme', 'light');
        $hostParam = $request->query('host') ?: $request->query('base_url');
        $baseUrl = $hostParam ? rtrim((string) $hostParam, '/') : rtrim(config('app.url') ?: $request->getSchemeAndHttpHost(), '/');

        $customHost = $hostParam ? rtrim((string) $hostParam, '/') : '';
        $repoUrl = config('services.github.repo_url', 'https://github.com/codebykenth/github-readme-generator');

        // Resolve username from token if not provided
        if (! $username) {
            $username = $this->github->getAuthenticatedUsername();
        }

        if (! $username) {
            return response('Error: Could not resolve username. Provide ?username= or set GITHUB_TOKEN.', 400);
        }

        try {
            $profile = $this->github->getUserProfile($username);
        } catch (\Throwable) {
            $profile = [
                'login' => $username,
                'name' => (string) $request->query('name', $username),
                'avatarUrl' => "https://avatars.githubusercontent.com/{$username}",
                'bio' => (string) $request->query('bio', ''),
                'company' => '',
                'location' => '',
                'websiteUrl' => '',
                'followers' => 0,
                'following' => 0,
                'repositories' => 0,
                'createdAt' => '',
            ];
        }

        $skills = array_filter(explode(',', (string) $request->query('skills', '')));
        $skillsStr = implode(',', $skills);

        $markdown = view('readme-template', [
            'profile' => $profile,
            'displayName' => (string) ($request->query('name') ?: ($profile['name'] ?: $profile['login'])),
            'username' => $username,
            'theme' => $theme,
            'baseUrl' => $baseUrl,
            'customHost' => $customHost,
            'repoUrl' => $repoUrl,
            'skills' => $skills,
            'skillsStr' => $skillsStr,
            'banner' => (string) $request->query('banner', ''),
            'tagline' => (string) $request->query('tagline', $profile['bio']),
            'typingLines' => (string) $request->query('typing_lines', ''),
            'workingOn' => (string) $request->query('working_on', ''),
            'learning' => (string) $request->query('learning', ''),
            'collaborateOn' => (string) $request->query('collaborate_on', ''),
            'helpWith' => (string) $request->query('help_with', ''),
            'askMe' => (string) $request->query('ask_me', ''),
            'reachMe' => (string) $request->query('reach_me', ''),
            'projectsUrl' => (string) $request->query('projects_url', ''),
            'articlesUrl' => (string) $request->query('articles_url', ''),
            'resumeUrl' => (string) $request->query('resume_url', ''),
            'funFact' => (string) $request->query('fun_fact', ''),
            'linkedin' => (string) $request->query('linkedin', ''),
            'twitter' => (string) $request->query('twitter', ''),
            'youtube' => (string) $request->query('youtube', ''),
            'discord' => (string) $request->query('discord', ''),
            'medium' => (string) $request->query('medium', ''),
            'devto' => (string) $request->query('devto', ''),
            'hashnode' => (string) $request->query('hashnode', ''),
            'stackoverflow' => (string) $request->query('stackoverflow', ''),
            'leetcode' => (string) $request->query('leetcode', ''),
            'website' => (string) $request->query('website', ''),
            'email' => (string) $request->query('email', ''),
            'buymeacoffee' => (string) $request->query('buymeacoffee', ''),
            'kofi' => (string) $request->query('kofi', ''),
            'patreon' => (string) $request->query('patreon', ''),
            'paypal' => (string) $request->query('paypal', ''),
            'visitorCount' => $request->boolean('visitor_count', true),
            'trophies' => $request->boolean('trophies', false),
            'quotes' => $request->boolean('quotes', false),
            'showStats' => $request->boolean('show_stats', true),
            'showLanguages' => $request->boolean('show_languages', true),
            'showStreak' => $request->boolean('show_streak', true),
            'showSnake' => $request->boolean('show_snake', true),
            'showProfile' => $request->boolean('show_profile', true),
            'showPinned' => $request->boolean('show_pinned', true),
        ])->render();

        return response($markdown)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
