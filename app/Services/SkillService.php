<?php

namespace App\Services;

class SkillService
{
    /**
     * Categorized list of supported skills with their display name and icon slug.
     *
     * @return array<string, array<int, array{id: string, name: string}>>
     */
    public static function categorized(): array
    {
        return [
            'Programming Languages' => [
                ['id' => 'js', 'name' => 'JavaScript'],
                ['id' => 'ts', 'name' => 'TypeScript'],
                ['id' => 'py', 'name' => 'Python'],
                ['id' => 'php', 'name' => 'PHP'],
                ['id' => 'go', 'name' => 'Go'],
                ['id' => 'rust', 'name' => 'Rust'],
                ['id' => 'java', 'name' => 'Java'],
                ['id' => 'cpp', 'name' => 'C++'],
                ['id' => 'cs', 'name' => 'C#'],
                ['id' => 'c', 'name' => 'C'],
                ['id' => 'ruby', 'name' => 'Ruby'],
                ['id' => 'swift', 'name' => 'Swift'],
                ['id' => 'kotlin', 'name' => 'Kotlin'],
                ['id' => 'dart', 'name' => 'Dart'],
                ['id' => 'r', 'name' => 'R'],
                ['id' => 'elixir', 'name' => 'Elixir'],
                ['id' => 'lua', 'name' => 'Lua'],
                ['id' => 'html', 'name' => 'HTML5'],
                ['id' => 'css', 'name' => 'CSS3'],
            ],
            'Frontend' => [
                ['id' => 'react', 'name' => 'React'],
                ['id' => 'nextjs', 'name' => 'Next.js'],
                ['id' => 'vue', 'name' => 'Vue.js'],
                ['id' => 'nuxtjs', 'name' => 'Nuxt.js'],
                ['id' => 'svelte', 'name' => 'Svelte'],
                ['id' => 'angular', 'name' => 'Angular'],
                ['id' => 'tailwind', 'name' => 'Tailwind CSS'],
                ['id' => 'bootstrap', 'name' => 'Bootstrap'],
                ['id' => 'sass', 'name' => 'Sass'],
                ['id' => 'vite', 'name' => 'Vite'],
                ['id' => 'redux', 'name' => 'Redux'],
                ['id' => 'webpack', 'name' => 'Webpack'],
            ],
            'Backend & Fullstack' => [
                ['id' => 'laravel', 'name' => 'Laravel'],
                ['id' => 'nodejs', 'name' => 'Node.js'],
                ['id' => 'express', 'name' => 'Express.js'],
                ['id' => 'nestjs', 'name' => 'NestJS'],
                ['id' => 'django', 'name' => 'Django'],
                ['id' => 'flask', 'name' => 'Flask'],
                ['id' => 'fastapi', 'name' => 'FastAPI'],
                ['id' => 'spring', 'name' => 'Spring Boot'],
                ['id' => 'rails', 'name' => 'Ruby on Rails'],
                ['id' => 'dotnet', 'name' => '.NET'],
                ['id' => 'graphql', 'name' => 'GraphQL'],
            ],
            'Mobile Development' => [
                ['id' => 'flutter', 'name' => 'Flutter'],
                ['id' => 'react', 'name' => 'React Native'],
                ['id' => 'androidstudio', 'name' => 'Android Studio'],
                ['id' => 'apple', 'name' => 'iOS'],
                ['id' => 'kotlin', 'name' => 'Kotlin Mobile'],
                ['id' => 'swift', 'name' => 'SwiftUI'],
            ],
            'Databases & Storage' => [
                ['id' => 'mysql', 'name' => 'MySQL'],
                ['id' => 'postgres', 'name' => 'PostgreSQL'],
                ['id' => 'mongodb', 'name' => 'MongoDB'],
                ['id' => 'redis', 'name' => 'Redis'],
                ['id' => 'sqlite', 'name' => 'SQLite'],
                ['id' => 'supabase', 'name' => 'Supabase'],
                ['id' => 'firebase', 'name' => 'Firebase'],
                ['id' => 'prisma', 'name' => 'Prisma'],
                ['id' => 'dynamodb', 'name' => 'DynamoDB'],
            ],
            'DevOps & Cloud' => [
                ['id' => 'docker', 'name' => 'Docker'],
                ['id' => 'kubernetes', 'name' => 'Kubernetes'],
                ['id' => 'git', 'name' => 'Git'],
                ['id' => 'githubactions', 'name' => 'GitHub Actions'],
                ['id' => 'linux', 'name' => 'Linux'],
                ['id' => 'aws', 'name' => 'AWS'],
                ['id' => 'gcp', 'name' => 'Google Cloud'],
                ['id' => 'azure', 'name' => 'Azure'],
                ['id' => 'nginx', 'name' => 'Nginx'],
                ['id' => 'vercel', 'name' => 'Vercel'],
                ['id' => 'netlify', 'name' => 'Netlify'],
                ['id' => 'cloudflare', 'name' => 'Cloudflare'],
                ['id' => 'terraform', 'name' => 'Terraform'],
            ],
            'AI / Data Science & Testing' => [
                ['id' => 'tensorflow', 'name' => 'TensorFlow'],
                ['id' => 'pytorch', 'name' => 'PyTorch'],
                ['id' => 'opencv', 'name' => 'OpenCV'],
                ['id' => 'jest', 'name' => 'Jest'],
                ['id' => 'cypress', 'name' => 'Cypress'],
            ],
            'Design & Developer Tools' => [
                ['id' => 'figma', 'name' => 'Figma'],
                ['id' => 'postman', 'name' => 'Postman'],
                ['id' => 'vscode', 'name' => 'VS Code'],
                ['id' => 'photoshop', 'name' => 'Photoshop'],
                ['id' => 'illustrator', 'name' => 'Illustrator'],
                ['id' => 'blender', 'name' => 'Blender'],
                ['id' => 'npm', 'name' => 'npm'],
            ],
        ];
    }

    /**
     * Flattened map of id => name.
     *
     * @return array<string, string>
     */
    public static function map(): array
    {
        $map = [];
        foreach (self::categorized() as $skills) {
            foreach ($skills as $skill) {
                $map[$skill['id']] = $skill['name'];
            }
        }

        return $map;
    }
}
