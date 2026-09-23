<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilr &mdash; Your profile. Actually yours.</title>
    <meta name="description" content="Generate dynamic contribution snakes, tech stack icons, and stats cards for your GitHub profile with zero server limits.">
    <meta property="og:title" content="Profilr &mdash; Your profile. Actually yours.">
    <meta property="og:description" content="Generate dynamic contribution snakes, tech stack icons, and stats cards for your GitHub profile with zero server limits.">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%2322c55e'/><path d='M8 7L3 12L8 17M16 7L21 12L16 17M14 4L10 20' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <a href="#main" class="skip-to-content">Skip to content</a>
    <nav class="site-nav">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <rect width="24" height="24" rx="6" fill="#22c55e" stroke="none"/>
                    <path d="M8 7L3 12L8 17M16 7L21 12L16 17M14 4L10 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Profilr</span>
            </a>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#workflow">Workflow</a>
                <a href="{{ $repoUrl }}" target="_blank">Repository</a>
            </div>
        </div>
    </nav>

    <main id="main">
        {{-- Hero Section: Asymmetric Split --}}
        <section class="hero-split">
            <div class="hero-content">
                <h1>Build a developer profile that stands out.</h1>
                <p>Generate animated contribution snakes, live stats cards, and clean tech stack blocks without wrestling with Markdown tables or managing GitHub API limits yourself.</p>
                <div class="hero-actions">
                    <a href="{{ route('builder') }}" class="btn btn-primary">Open the Builder</a>
                    <a href="{{ $repoUrl }}" target="_blank" class="btn btn-secondary">View Source</a>
                </div>
                <div class="hero-meta">
                    100% client-side privacy. Zero server retention.
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="showcase-wrapper">
                    <div class="showcase-header">
                        <span class="showcase-label">Live Preview</span>
                        <div class="showcase-tabs">
                            <button type="button" class="showcase-tab active" onclick="switchTeaserTab('full', this)">Profile</button>
                            <button type="button" class="showcase-tab" onclick="switchTeaserTab('snake', this)">Snake</button>
                            <button type="button" class="showcase-tab" onclick="switchTeaserTab('stats', this)">Stats</button>
                        </div>
                    </div>

                    <div class="showcase-preview-body" id="teaser-preview-container">
                        <div id="teaser-view-full" class="github-rendered-preview">
                            <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 12px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">Hi there, I'm Alex Rivera</h1>
                            <p style="color: var(--text-secondary); margin-bottom: 16px;">A passionate full-stack developer crafting open source software &amp; modern web applications.</p>
                            
                            <img src="https://skillicons.dev/icons?i=php,laravel,react,typescript,tailwind,docker" alt="Skills" style="margin-bottom: 24px; display: block;" />
                            
                            <img src="https://raw.githubusercontent.com/Platane/snk/output/github-contribution-grid-snake.svg" alt="Snake animation" style="width: 100%; border-radius: 6px; background: rgba(255,255,255,0.02);" />
                        </div>

                        <div id="teaser-view-snake" class="github-rendered-preview" style="display: none; padding: 20px 0;">
                            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 24px; text-align: center;">
                                An animated snake consumes your actual GitHub contribution graph.
                            </p>
                            <img src="https://raw.githubusercontent.com/Platane/snk/output/github-contribution-grid-snake.svg" alt="Snake animation preview" style="width: 100%; border-radius: 6px; background: rgba(255,255,255,0.02);" />
                        </div>

                        <div id="teaser-view-stats" class="github-rendered-preview" style="display: none; padding: 20px 0;">
                            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 24px; text-align: center;">
                                Dynamic cards for top languages and total statistics.
                            </p>
                            <div style="display: flex; flex-direction: column; gap: 16px; align-items: center;">
                                <img src="https://github-stats-extended.vercel.app/api?username=torvalds&amp;show_icons=true&amp;theme=radical&amp;locale=en" alt="GitHub Stats" style="max-width: 100%;" />
                                <img src="https://github-stats-extended.vercel.app/api/top-langs/?username=torvalds&amp;layout=compact&amp;theme=radical" alt="Top Languages" style="max-width: 100%;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section: Asymmetric Alternation --}}
        <section class="section features" id="features">
            <div class="feature-row">
                <div class="feature-text">
                    <h2>Everything runs locally.</h2>
                    <p>Your GitHub handle, biography, and credentials never touch our servers. The builder operates entirely within your browser to generate clean, pasteable Markdown.</p>
                </div>
                <div class="feature-visual code-block">
                    <code>// Zero data retention
<span class="token-keyword">const</span> builder = <span class="token-keyword">new</span> ProfileBuilder({
  mode: <span class="token-string">'client-only'</span>,
  storage: <span class="token-keyword">null</span>,
  telemetry: <span class="token-keyword">false</span>
});
builder.render();</code>
                </div>
            </div>

<div class="feature-row reverse">
                <div class="feature-text">
                    <h2>CDN-first widgets.</h2>
                    <p>Community mode loads widgets from third-party public services, which set their own quotas and can occasionally rate-limit. Self-host with your GitHub token to use your own API quota, unlock private-repo stats, and drop the third-party dependency.</p>
                </div>
                <div class="feature-visual">
                    <div class="stats-mockup">
                        <div class="stat-line"><span class="label">Uptime</span><span class="value">99.99%</span></div>
                        <div class="stat-line"><span class="label">CDN Edge</span><span class="value">Global</span></div>
                        <div class="stat-line"><span class="label">Community mode</span><span class="value">CDN quotas</span></div>
                    </div>
                </div>
            </div>
            
            <div class="feature-row">
                <div class="feature-text">
                    <h2>Self-host for private stats.</h2>
                    <p>Deploy your own instance on Vercel with a single click. Configure your personal GitHub token as an environment variable to unlock analytics for private repositories.</p>
                </div>
                <div class="feature-visual">
                    <a href="https://vercel.com/new/clone?repository-url={{ urlencode($repoUrl) }}&amp;env=GITHUB_TOKEN" target="_blank" class="deploy-card">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M24 22.525H0l12-21.05 12 21.05z"/></svg>
                        <span>Deploy to Vercel</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- Workflow Section --}}
        <section class="section workflow" id="workflow">
            <div class="workflow-header">
                <h2>From zero to deployed in minutes.</h2>
            </div>
            
            <div class="workflow-steps">
                <div class="workflow-step">
                    <div class="step-num">01</div>
                    <h3>Configure</h3>
                    <p>Enter your GitHub handle, select your tech stack, and toggle the widgets you want.</p>
                </div>
                <div class="workflow-step">
                    <div class="step-num">02</div>
                    <h3>Preview</h3>
                    <p>Watch your README render in real-time as you tweak layouts and themes.</p>
                </div>
                <div class="workflow-step">
                    <div class="step-num">03</div>
                    <h3>Deploy</h3>
                    <p>Copy the generated Markdown and paste it into your profile repository.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-left">
                <span class="footer-brand">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#22c55e"/><path d="M8 7L3 12L8 17M16 7L21 12L16 17M14 4L10 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Profilr
                </span>
                <span class="separator">&bull;</span>
                <a href="{{ $repoUrl }}" target="_blank">Open Source (MIT)</a>
            </div>
            <div class="footer-right">
                <a href="#main">Back to top</a>
            </div>
        </div>
    </footer>

    <script>
        function switchTeaserTab(type, btn) {
            document.querySelectorAll('.showcase-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const views = ['full', 'snake', 'stats'];
            views.forEach(v => {
                const el = document.getElementById('teaser-view-' + v);
                if (el) el.style.display = (v === type) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>

