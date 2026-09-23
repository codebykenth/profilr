<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilr &mdash; Builder</title>
    <meta name="description" content="Interactive visual builder for your GitHub profile README. Add dynamic stats, snake animation, skills, socials, and instant copy-paste markdown.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/builder.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%2322c55e'/><path d='M8 7L3 12L8 17M16 7L21 12L16 17M14 4L10 20' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/></svg>">
</head>
<body>
    {{-- App Header --}}
    <header class="app-header">
        <div class="header-left">
            <a href="{{ route('home') }}" class="back-link">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M15 8a.75.75 0 01-.75.75H4.06l3.72 3.72a.75.75 0 11-1.06 1.06l-5-5a.75.75 0 010-1.06l5-5a.75.75 0 011.06 1.06L4.06 7.25H14.25A.75.75 0 0115 8z"/></svg>
                <span>Back to Overview</span>
            </a>
            <div class="app-title-group">
                <span style="font-weight: 700; font-size: 15px;">Profilr</span>
                <span class="app-badge">Interactive Workspace</span>
                <span class="online-pill" title="People currently using Profilr">
                    <span class="online-dot"></span>
                    <span><span data-online-count>1</span> online</span>
                </span>
                <span id="header-user-badge" class="app-badge" style="display: none; background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.3);">@<span id="header-username-text"></span></span>
            </div>
        </div>

        <div class="header-right" id="header-actions" style="display: none;">
            <div class="status-pill" title="Your changes update instantly here — no manual save needed">
                <span class="dot"></span>
                <span>Live Preview Active</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="copy-btn-header" onclick="copyFullReadme(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy
            </button>
            <div class="header-action-menu">
                <button type="button" class="icon-btn" id="more-actions-btn" onclick="toggleHeaderMenu(event)" aria-haspopup="true" aria-expanded="false" aria-label="More actions" title="More actions">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="2.4"/><circle cx="12" cy="12" r="2.4"/><circle cx="12" cy="19" r="2.4"/></svg>
                </button>
                <div class="header-action-dropdown" id="header-action-dropdown">
                    <button type="button" class="menu-item" onclick="downloadReadme()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Download .md
                    </button>
                    <div class="menu-divider"></div>
                    <button type="button" class="menu-item danger" onclick="resetWorkspacePrompt()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg> Reset workspace
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Onboarding Username Gate --}}
    <div class="onboarding-overlay" id="onboarding-gate">
        <div class="onboarding-card">
            <div class="onboarding-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="6" fill="#22c55e"/><path d="M8 7L3 12L8 17M16 7L21 12L16 17M14 4L10 20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h1 class="onboarding-title">Welcome to Profilr</h1>
            <p class="onboarding-desc">Enter your GitHub username to begin building your profile README.</p>
            <div class="onboarding-input-group">
                <input type="text" id="onboarding-username-input" class="onboarding-input" placeholder="e.g. torvalds, johndoe..." autofocus onkeydown="if(event.key==='Enter'){event.preventDefault();submitOnboarding();}" />
                <button type="button" class="btn btn-primary" style="padding: 14px 20px; font-size: 15px;" onclick="submitOnboarding()">
                    Open Workspace &rarr;
                </button>
            </div>
            <div id="onboarding-error" style="display: none; color: var(--danger); font-size: 13px; margin-top: 8px;">Please enter a valid GitHub username to proceed.</div>
        </div>
    </div>

    {{-- Main Split-Screen Workspace --}}
    <div class="builder-layout" id="builder-workspace" style="display: none;">
        {{-- Left Panel: Step-by-Step Configurator --}}
        <section class="builder-panel-left">
            {{-- Horizontal Step Navigation Bar --}}
            <nav class="step-nav" id="step-nav">
                <button type="button" class="step-nav-arrow" id="step-nav-prev" onclick="scrollStepNav(-1)" aria-label="Scroll tabs left" title="Scroll tabs left">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 4 9 12 15 20"/></svg>
                </button>
                <div class="step-nav-bar" id="step-nav-bar">
                    <button type="button" class="step-tab-btn active" data-step="1" onclick="switchStep(1)">
                        <span>👤</span> <span class="step-num">01</span> Profile
                    </button>
                    <button type="button" class="step-tab-btn" data-step="2" onclick="switchStep(2)">
                        <span>🎨</span> <span class="step-num">02</span> Theme
                    </button>
                    <button type="button" class="step-tab-btn" data-step="3" onclick="switchStep(3)">
                        <span>🛠️</span> <span class="step-num">03</span> Tech Stack <span class="step-tab-badge" id="skills-badge-count">6</span>
                    </button>
                    <button type="button" class="step-tab-btn" data-step="4" onclick="switchStep(4)">
                        <span>💼</span> <span class="step-num">04</span> Projects <span class="step-tab-badge" id="projects-badge-count">1</span>
                    </button>
                    <button type="button" class="step-tab-btn" data-step="5" onclick="switchStep(5)">
                        <span>🌐</span> <span class="step-num">05</span> Socials
                    </button>
                    <button type="button" class="step-tab-btn" data-step="6" onclick="switchStep(6)">
                        <span>☕</span> <span class="step-num">06</span> Support
                    </button>
                    <button type="button" class="step-tab-btn" data-step="7" onclick="switchStep(7)">
                        <span>📈</span> <span class="step-num">07</span> Add-ons
                    </button>
                    <button type="button" class="step-tab-btn" data-step="8" onclick="switchStep(8)">
                        <span>🚀</span> <span class="step-num">08</span> Hosting
                    </button>
                </div>
                <button type="button" class="step-nav-arrow" id="step-nav-next" onclick="scrollStepNav(1)" aria-label="Scroll tabs right" title="Scroll tabs right">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 4 15 12 9 20"/></svg>
                </button>
            </nav>

            {{-- Step Panes Container --}}
            <div class="step-content-scroll">
                {{-- Step 1: Identity & Bio --}}
                <div class="step-pane active" id="step-pane-1">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>👤</span> Header &amp; Profile Information</div>
                            <div class="step-desc">Introduce yourself, add an optional header banner, and highlight what you're working on.</div>
                        </div>
                    </div>

                    <div class="form-group" id="sections-order-container" style="margin-bottom: 20px;">
                        <label class="form-label">
                            <span>README Section Order</span>
                            <span class="hint">Click &larr; &rarr; to reorder sections in your README preview. Use 👁 to hide/show an add-on section. Dimmed chips are hidden (no content / turned off).</span>
                        </label>
                        <div class="reorder-strip" id="sections-reorder-strip"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="config-username">
                                <span>GitHub Username *</span>
                                <span class="hint">Required for stats &amp; widgets</span>
                            </label>
                            <input type="text" id="config-username" class="form-input" value="{{ $defaultUsername ?: 'your-username' }}" placeholder="e.g. torvalds" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="config-display-name">
                                <span>Display Name / Headline</span>
                                <span class="hint">Shown in greeting</span>
                            </label>
                            <input type="text" id="config-display-name" class="form-input" placeholder="e.g. Alex Rivera (Full-Stack Engineer)" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="config-bio">
                            <span>Tagline / Subtitle</span>
                            <span class="hint">Brief bio quote</span>
                        </label>
                        <input type="text" id="config-bio" class="form-input" placeholder="e.g. A passionate developer building open source apps 🚀" />
                    </div>

                    {{-- Dynamic Header Banner Selector --}}
                    <div class="banner-config-card">
                        <label class="form-label">
                            <span>🖼️ Header Banner</span>
                            <span class="hint">Pick an animated preset or provide a custom image link</span>
                        </label>

                        <div class="banner-mode-pills">
                            <button type="button" class="banner-pill-btn active" data-banner-mode="preset" onclick="setBannerMode('preset')">
                                🎨 Dynamic Banner Styles
                            </button>
                            <button type="button" class="banner-pill-btn" data-banner-mode="custom" onclick="setBannerMode('custom')">
                                🔗 Custom Image Link
                            </button>
                            <button type="button" class="banner-pill-btn" data-banner-mode="none" onclick="setBannerMode('none')">
                                🚫 No Banner
                            </button>
                        </div>

                        {{-- Preset Styles Grid --}}
                        <div id="banner-presets-section">
                            <div class="banner-presets-grid">
                                <button type="button" class="banner-preset-card active" data-preset-key="waving-gradient" onclick="selectBannerPreset('waving-gradient')">
                                    <div class="banner-preset-swatch swatch-waving-gradient"></div>
                                    <div class="banner-preset-name">Wave Gradient</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="soft-emerald" onclick="selectBannerPreset('soft-emerald')">
                                    <div class="banner-preset-swatch swatch-soft-emerald"></div>
                                    <div class="banner-preset-name">Soft Emerald</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="indigo-violet" onclick="selectBannerPreset('indigo-violet')">
                                    <div class="banner-preset-swatch swatch-indigo-violet"></div>
                                    <div class="banner-preset-name">Indigo Violet</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="sunset-glow" onclick="selectBannerPreset('sunset-glow')">
                                    <div class="banner-preset-swatch swatch-sunset-glow"></div>
                                    <div class="banner-preset-name">Sunset Glow</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="ocean-blue" onclick="selectBannerPreset('ocean-blue')">
                                    <div class="banner-preset-swatch swatch-ocean-blue"></div>
                                    <div class="banner-preset-name">Ocean Blue</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="cyberpunk-slice" onclick="selectBannerPreset('cyberpunk-slice')">
                                    <div class="banner-preset-swatch swatch-cyberpunk-slice"></div>
                                    <div class="banner-preset-name">Cyberpunk Slice</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="cylinder-dark" onclick="selectBannerPreset('cylinder-dark')">
                                    <div class="banner-preset-swatch swatch-cylinder-dark"></div>
                                    <div class="banner-preset-name">Stealth Dark</div>
                                </button>
                                <button type="button" class="banner-preset-card" data-preset-key="minimal-rect" onclick="selectBannerPreset('minimal-rect')">
                                    <div class="banner-preset-swatch swatch-minimal-rect"></div>
                                    <div class="banner-preset-name">Carbon Rect</div>
                                </button>
                            </div>

                            <div class="banner-extra-fields">
                                <div class="form-group">
                                    <label class="form-label" for="config-banner-text">
                                        <span>Banner Title</span>
                                        <span class="hint">Defaults to your name</span>
                                    </label>
                                    <input type="text" id="config-banner-text" class="form-input" placeholder="Leave blank to use your name" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="config-banner-desc">
                                        <span>Banner Subtitle</span>
                                        <span class="hint">Optional second line</span>
                                    </label>
                                    <input type="text" id="config-banner-desc" class="form-input" placeholder="e.g. Full-Stack Developer 🚀" />
                                </div>
                            </div>
                        </div>

                        {{-- Custom Image URL Section --}}
                        <div id="banner-custom-section" style="display: none;">
                            <div class="form-group">
                                <label class="form-label" for="config-banner-custom">
                                    <span>Direct Image Banner URL</span>
                                    <span class="hint">PNG, GIF, JPG, or SVG link</span>
                                </label>
                                <input type="url" id="config-banner-custom" class="form-input" placeholder="https://example.com/my-banner.png" />
                            </div>
                        </div>

                        {{-- Live Miniature Banner Preview --}}
                        <div class="banner-live-preview-box" id="banner-live-preview-box">
                            <div class="banner-preview-label">Live Banner Preview:</div>
                            <img id="banner-thumbnail-img" src="" alt="Banner Preview" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label" for="config-typing-lines">
                            <span>Dynamic Typing Intro Lines</span>
                            <span class="hint">Semicolon-separated animated text</span>
                        </label>
                        <input type="text" id="config-typing-lines" class="form-input" value="Full-Stack Developer; Laravel &amp; React Enthusiast; Open Source Builder" />
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label"><span>About Me Bullets (Optional)</span></label>
                        <div class="form-row">
                            <input type="text" id="config-working-on" class="form-input" placeholder="🔭 Currently working on..." />
                            <input type="text" id="config-learning" class="form-input" placeholder="🌱 Currently learning..." />
                        </div>
                        <div class="form-row">
                            <input type="text" id="config-collaborate-on" class="form-input" placeholder="👯 Looking to collaborate on..." />
                            <input type="text" id="config-help-with" class="form-input" placeholder="🤝 Looking for help with..." />
                        </div>
                        <div class="form-row">
                            <input type="text" id="config-ask-me" class="form-input" placeholder="💬 Ask me about..." />
                            <input type="text" id="config-reach-me" class="form-input" placeholder="📫 How to reach me..." />
                        </div>
                        <div class="form-row">
                            <input type="text" id="config-projects-url" class="form-input" placeholder="👨‍💻 Portfolio / Projects URL..." />
                            <input type="text" id="config-resume-url" class="form-input" placeholder="📄 Resume URL..." />
                        </div>
                        <div class="form-row">
                            <input type="text" id="config-articles-url" class="form-input" placeholder="📝 Blog / Articles URL..." />
                            <input type="text" id="config-fun-fact" class="form-input" placeholder="⚡ Fun fact about you..." />
                        </div>
                    </div>

                    <div class="custom-section-box">
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>✨</span> Custom Bio &amp; About Points
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addCustomBioItem()">+ Add Custom Point</button>
                        </div>
                        <div id="custom-bio-list" class="custom-repeater-list"></div>
                    </div>

                    <div class="step-footer-nav">
                        <div></div>
                        <button type="button" class="btn btn-primary" onclick="switchStep(2)">Next: Theme →</button>
                    </div>
                </div>

                {{-- Step 3: Tech Stack & Skills --}}
                <div class="step-pane" id="step-pane-3">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>🛠️</span> Tech Stack &amp; Skills</div>
                            <div class="step-desc">Pick your languages, frameworks, databases, and developer tools. Generates sleek skill icons via skillicons.dev.</div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearSkills()">Clear All</button>
                    </div>

                    <div class="skills-search-wrapper">
                        <span class="skills-search-icon">🔍</span>
                        <input
                            type="text"
                            id="skills-search-input"
                            class="skills-search-input"
                            placeholder="Search skills (e.g. PHP, Laravel, React, Docker, Python)..."
                            oninput="onSkillsSearchInput(this.value)"
                            onkeydown="if(event.key==='Escape'){clearSkillsSearch();}"
                        />
                        <button type="button" id="skills-search-clear-btn" class="skills-search-clear-btn" onclick="clearSkillsSearch()" style="display: none;" title="Clear search">&times;</button>
                        <span id="skills-search-count-badge" class="skills-search-count-badge"></span>
                    </div>

                    <div class="skills-filter-pills" id="skills-filter-pills">
                        <button type="button" class="skills-pill-btn active" data-category="all" onclick="setSkillCategoryFilter('all')">All</button>
                        <button type="button" class="skills-pill-btn" data-category="selected" onclick="setSkillCategoryFilter('selected')">⭐ Selected (<span id="skills-selected-pill-count">6</span>)</button>
                        @foreach ($skillCategories as $categoryName => $skills)
                            <button type="button" class="skills-pill-btn" data-category="{{ $categoryName }}" onclick="setSkillCategoryFilter('{{ $categoryName }}')">
                                {{ $categoryName }}
                            </button>
                        @endforeach
                    </div>

                    <div class="selected-skills-tags" id="selected-skills-tags"></div>

                    <div class="form-group">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                            <label class="form-label" style="margin-bottom: 0;">
                                <span>Live Tech Stack Preview Strip</span>
                                <span id="skills-detected-badge" style="display: none; background: var(--accent-dark); border: 1px solid var(--border-highlight); color: var(--accent); padding: 2px 8px; border-radius: var(--radius-sm); font-size: 11px; font-family: var(--font-mono); margin-left: 6px;">⚡ Auto-detected</span>
                            </label>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-detect-langs" onclick="detectTopLanguages(state.username, true)" title="Analyze your GitHub repositories and auto-select your top languages" style="font-size: 11px; padding: 4px 10px;">
                                    <span id="detect-langs-icon">⚡</span> <span id="detect-langs-text">Auto-Detect from My GitHub</span>
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="clearSkills()" style="font-size: 11px; padding: 4px 8px; color: var(--text-muted);" title="Clear all selected skills">
                                    Clear All
                                </button>
                            </div>
                        </div>
                        <div class="skills-preview-box" id="skills-preview-box">
                            <div id="skills-detecting-loader" class="skills-detecting-loader" style="display: none;">
                                <span class="detect-spinner">⚡</span>
                                <span id="skills-detecting-text">Analyzing GitHub repositories for most used languages...</span>
                            </div>
                            <img id="skills-strip-img" src="https://skillicons.dev/icons?i=php,laravel,react,tailwind,docker,mysql" alt="Skills Preview" />
                            <div id="skills-empty-strip-hint" style="display: none; color: var(--text-muted); font-size: 13px; font-style: italic;">
                                No skills selected. Click any skill chip below or enter your GitHub handle!
                            </div>
                        </div>
                    </div>

                    <div id="skills-catalog">
                        <div id="skills-empty-state" class="skills-empty-state" style="display: none;">
                            <div style="font-size: 24px; margin-bottom: 8px;">🔍</div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">No skills matching "<span id="skills-empty-query"></span>"</div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Looking for something not in our catalog? Add it as a custom skill or badge below!</div>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="clearSkillsSearch()">Clear Filter</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="useQueryAsCustomSkill()">+ Add as Custom Skill</button>
                            </div>
                        </div>
                        @foreach ($skillCategories as $categoryName => $skills)
                            <div class="skills-category-group" data-category="{{ $categoryName }}">
                                <div class="skills-category-name">{{ $categoryName }}</div>
                                <div class="skills-chips-grid">
                                    @foreach ($skills as $skill)
                                        <button
                                            type="button"
                                            class="skill-chip"
                                            data-skill-id="{{ $skill['id'] }}"
                                            data-skill-name="{{ $skill['name'] }}"
                                            onclick="toggleSkill('{{ $skill['id'] }}')"
                                        >
                                            <img
                                                class="skill-icon"
                                                src="https://skillicons.dev/icons?i={{ $skill['id'] }}"
                                                alt="{{ $skill['name'] }}"
                                                loading="lazy"
                                                onerror="this.style.display='none'"
                                            />
                                            <span>{{ $skill['name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="custom-section-box">
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>➕</span> Add Custom Skill (Any Skillicons Slug or Image URL)
                            </div>
                        </div>
                        <div style="display: flex; gap: 8px; margin-bottom: 14px;">
                            <input type="text" id="custom-skill-input" class="form-input" placeholder="Enter skill slug or paste direct image URL..." onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomSkill();}" />
                            <button type="button" class="btn btn-primary btn-sm" onclick="addCustomSkill()">Add</button>
                        </div>
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>🛡️</span> Custom Tech Badges &amp; Direct Image URLs
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addCustomTechBadge()">+ Add Custom Tech</button>
                        </div>
                        <div class="custom-section-hint">
                            For tools not in Skillicons (like n8n, PocketBase, or custom icons), paste a direct image URL or generate a custom Shields.io badge.
                        </div>
                        <div id="custom-tech-badges-list" class="custom-repeater-list"></div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(2)">← Back: Theme</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(4)">Next: Projects →</button>
                    </div>
                </div>

                {{-- Step 4: Featured Projects & Showcase --}}
                <div class="step-pane" id="step-pane-4">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>💼</span> Featured Projects &amp; Showcase</div>
                            <div class="step-desc">Showcase your top projects, open source apps, and repositories with image thumbnails, live demos, and tech tags.</div>
                        </div>
                    </div>

                    <div class="custom-section-box">
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>🚀</span> Project Showcase Cards
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addSampleProject()">✨ Add Sample Project</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addProject()">+ Add Project</button>
                            </div>
                        </div>
                        <div class="custom-section-hint">
                            Add image thumbnails, descriptions, and repository/live demo links. Your README will render these as clean, responsive project cards.
                        </div>
                        <div id="projects-list" class="projects-repeater-list"></div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(3)">← Back: Tech Stack</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(5)">Next: Socials →</button>
                    </div>
                </div>

                {{-- Step 5: Socials & Contact --}}
                <div class="step-pane" id="step-pane-5">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>🌐</span> Social Networks &amp; Profiles</div>
                            <div class="step-desc">Enter your profile URLs to automatically render official Shields.io badges.</div>
                        </div>
                    </div>

                    <div class="form-group" id="socials-order-container" style="display: none; margin-bottom: 20px;">
                        <label class="form-label">
                            <span>Badge Display Order</span>
                            <span class="hint">Click &larr; &rarr; to reorder how badges appear in your README</span>
                        </label>
                        <div class="reorder-strip" id="socials-reorder-strip"></div>
                    </div>

                    <div class="platform-grid">
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">💼 LinkedIn</span></div>
                            <input type="url" id="config-linkedin" class="form-input" placeholder="https://linkedin.com/in/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">𝕏 Twitter / X</span></div>
                            <input type="url" id="config-twitter" class="form-input" placeholder="https://x.com/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">▶️ YouTube</span></div>
                            <input type="url" id="config-youtube" class="form-input" placeholder="https://youtube.com/@channel" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">💬 Discord</span></div>
                            <input type="text" id="config-discord" class="form-input" placeholder="Discord handle or invite" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">📝 Medium</span></div>
                            <input type="url" id="config-medium" class="form-input" placeholder="https://medium.com/@username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">👩‍💻 DEV.to</span></div>
                            <input type="url" id="config-devto" class="form-input" placeholder="https://dev.to/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🔷 Hashnode</span></div>
                            <input type="url" id="config-hashnode" class="form-input" placeholder="https://hashnode.com/@username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🥞 Stack Overflow</span></div>
                            <input type="url" id="config-stackoverflow" class="form-input" placeholder="https://stackoverflow.com/users/..." />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🧩 LeetCode</span></div>
                            <input type="url" id="config-leetcode" class="form-input" placeholder="https://leetcode.com/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">📸 Instagram</span></div>
                            <input type="url" id="config-instagram" class="form-input" placeholder="https://instagram.com/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🟣 Twitch</span></div>
                            <input type="url" id="config-twitch" class="form-input" placeholder="https://twitch.tv/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">📊 Kaggle</span></div>
                            <input type="url" id="config-kaggle" class="form-input" placeholder="https://kaggle.com/username" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🌐 Personal Website</span></div>
                            <input type="url" id="config-website" class="form-input" placeholder="https://yourportfolio.dev" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">✉️ Email Address</span></div>
                            <input type="email" id="config-email" class="form-input" placeholder="dev@example.com" />
                        </div>
                    </div>

                    <div class="custom-section-box">
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>🌐</span> Custom Social Networks (Bluesky, TikTok, Threads, Substack, etc.)
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addCustomSocial()">+ Add Custom Social</button>
                        </div>
                        <div id="custom-socials-list" class="custom-repeater-list"></div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(4)">← Back: Projects</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(6)">Next: Support →</button>
                    </div>
                </div>

                {{-- Step 6: Support & Sponsorship --}}
                <div class="step-pane" id="step-pane-6">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>☕</span> Support &amp; Sponsorship</div>
                            <div class="step-desc">Enable coffee and donation badges so followers can sponsor your work.</div>
                        </div>
                    </div>

                    <div class="platform-grid">
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">☕ Buy Me A Coffee</span></div>
                            <input type="text" id="config-buymeacoffee" class="form-input" placeholder="Username (e.g. alexrivera)" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🍵 Ko-fi</span></div>
                            <input type="text" id="config-kofi" class="form-input" placeholder="Username (e.g. alexrivera)" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">🧡 Patreon</span></div>
                            <input type="text" id="config-patreon" class="form-input" placeholder="Username (e.g. alexrivera)" />
                        </div>
                        <div class="platform-input-card">
                            <div class="platform-card-header"><span class="platform-card-title">💳 PayPal</span></div>
                            <input type="text" id="config-paypal" class="form-input" placeholder="PayPal.me username" />
                        </div>
                    </div>

                    <div class="custom-section-box">
                        <div class="custom-section-header">
                            <div class="custom-section-title">
                                <span>☕</span> Custom Support &amp; Donation Platforms (GitHub Sponsors, Open Collective, etc.)
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addCustomSupport()">+ Add Custom Support</button>
                        </div>
                        <div id="custom-support-list" class="custom-repeater-list"></div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(5)">← Back: Socials</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(7)">Next: Add-ons →</button>
                    </div>
                </div>

                {{-- Step 7: Add-ons, Snake & Analytics --}}
                <div class="step-pane" id="step-pane-7">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>📈</span> Add-ons, Snake Animation &amp; Analytics</div>
                            <div class="step-desc">Toggle the interactive contribution eating snake animation, visitor counter, trophies, quotes, and stats cards.</div>
                        </div>
                    </div>

                    <div class="addons-grid" style="margin-bottom: 20px;">
                        <label class="addon-card active" id="card-addon-snake">
                            <input type="checkbox" id="addon-snake" class="addon-checkbox" checked onchange="toggleAddon('snake', this.checked)" />
                            <div>
                                <div class="addon-info-title">🐍 Contribution Graph Snake Animation</div>
                                <div class="addon-info-desc">Snake animation eating your GitHub contributions grid in real time.</div>
                            </div>
                        </label>

                        <label class="addon-card active" id="card-addon-visitor">
                            <input type="checkbox" id="addon-visitor-count" class="addon-checkbox" checked onchange="toggleAddon('visitorCount', this.checked)" />
                            <div>
                                <div class="addon-info-title">👁️ Profile Visitor Counter Badge</div>
                                <div class="addon-info-desc">Real-time visitor count badge via Komarev.</div>
                            </div>
                        </label>

                        <label class="addon-card" id="card-addon-trophies">
                            <input type="checkbox" id="addon-trophies" class="addon-checkbox" onchange="toggleAddon('trophies', this.checked)" />
                            <div>
                                <div class="addon-info-title">🏆 GitHub Profile Trophies</div>
                                <div class="addon-info-desc">Dynamic trophies for your stars, commits, issues, and PRs.</div>
                            </div>
                        </label>

                        <label class="addon-card" id="card-addon-quotes">
                            <input type="checkbox" id="addon-quotes" class="addon-checkbox" onchange="toggleAddon('quotes', this.checked)" />
                            <div>
                                <div class="addon-info-title">💬 Random Dev Quotes</div>
                                <div class="addon-info-desc">Inspiring programming quotes updated on every visit.</div>
                            </div>
                        </label>
                    </div>

                    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-weight: 700; font-size: 13px; margin-bottom: 10px;">📊 Dynamic GitHub Analytics Cards</div>
                        <div class="widget-toggle-list" style="margin-bottom: 14px;">
                            <button type="button" class="toggle-chip active" data-widget-key="stats" onclick="toggleWidget('stats')">
                                <span>📊</span> Stats Card
                            </button>
                            <button type="button" class="toggle-chip active" data-widget-key="languages" onclick="toggleWidget('languages')">
                                <span>💻</span> Top Languages
                            </button>
                            <button type="button" class="toggle-chip active" data-widget-key="streak" onclick="toggleWidget('streak')">
                                <span>🔥</span> Streak Stats
                            </button>
                        </div>
                    </div>

                    <div style="background: rgba(234, 179, 8, 0.08); border: 1px solid rgba(234, 179, 8, 0.25); border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; line-height: 1.6; color: var(--text-secondary);">
                        <div style="font-weight: 700; color: #eab308; margin-bottom: 6px;">⚠️ Free CDN Mode Limitations</div>
                        <ul style="margin: 0; padding-left: 18px;">
                            <li><strong>Snake Animation</strong> and <strong>Trophies</strong> rely on third-party services that may be rate-limited or unavailable. They work best on a self-hosted instance.</li>
                            <li><strong>Stats, Languages &amp; Streak</strong> widgets only count <strong>public repository</strong> data when using free CDNs.</li>
                            <li>To include <strong>private repo stats</strong>, unlock all add-ons reliably, and get your own API quota — <strong>self-host this app</strong>, add your <code style="background: rgba(255,255,255,0.08); padding: 1px 5px; border-radius: 4px;">GITHUB_TOKEN</code>, and switch to <strong>Custom Host</strong> mode in the Hosting step.</li>
                        </ul>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(6)">← Back: Support</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(8)">Next: Hosting →</button>
                    </div>
                </div>

                {{-- Step 2: Overall Theme --}}
                <div class="step-pane" id="step-pane-2">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>🎨</span> Overall Theme Configuration</div>
                            <div class="step-desc">Select a global theme preset that applies to all dynamic components, stats cards, and animations in your README.</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="margin-bottom: 12px; font-size: 14px;"><span>Select a Preset Theme</span></label>
                        <div class="theme-bar" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach ($themes as $themeName)
                                <button
                                    type="button"
                                    class="theme-chip {{ $themeName === 'light' ? 'active' : '' }}"
                                    data-theme="{{ $themeName }}"
                                    onclick="selectTheme('{{ $themeName }}')"
                                    style="padding: 10px 16px; font-size: 14px;"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $themeName)) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(1)">← Back: Profile</button>
                        <button type="button" class="btn btn-primary" onclick="switchStep(3)">Next: Tech Stack →</button>
                    </div>
                </div>

                {{-- Step 8: Hosting Mode --}}
                <div class="step-pane" id="step-pane-8">
                    <div class="step-header">
                        <div>
                            <div class="step-title"><span>🚀</span> Hosting Mode &amp; Dedicated Deployment</div>
                            <div class="step-desc">Choose where your dynamic widgets load from. Both options work — pick based on whether you have deployed your own instance.</div>
                        </div>
                    </div>

                    <div class="deploy-decision-box">
                        <div class="deploy-decision-title">🤔 Which option should I pick?</div>
                        <button type="button" class="deploy-decision-item active" id="btn-mode-community" onclick="setHostMode('community')">
                            <div class="deploy-decision-head">⚡ Instant Copy-Paste <span class="deploy-decision-badge">Best for most people</span></div>
                            <div class="deploy-decision-text">Zero setup. Copy the Markdown and paste it into your profile — widgets load from public community CDNs using your username. Choose this unless you've already deployed your own instance.</div>
                        </button>
                        <button type="button" class="deploy-decision-item" id="btn-mode-custom" onclick="setHostMode('custom')">
                            <div class="deploy-decision-head">▲ My Deployed Instance <span class="deploy-decision-badge rec">Advanced</span></div>
                            <div class="deploy-decision-text">You must fork &amp; deploy your own copy to Vercel <em>first</em>, then paste its URL below. Unlocks private-repo stats, your own GitHub API quota, and drops the third-party dependency.</div>
                        </button>
                    </div>

                    <div id="community-host-notice" class="community-host-notice">
                        ✨ <strong>Ready out-of-the-box:</strong> Generated Markdown uses public, high-availability community endpoints (GitHub Readme Stats, Streak Stats, Platane Snake, Skillicons, Shields.io). Zero requests ever touch our server — the widgets load directly from public CDNs using <strong>your GitHub username</strong>.
                        <div style="margin-top: 8px; font-size: 12px; opacity: 0.9;">
                            💡 Your <code>GITHUB_TOKEN</code> is <strong>not used here</strong>. It only powers the <code>/api/*</code> endpoints on your own deployment (see the "My Deployed Instance" option).
                        </div>
                    </div>

                    <div id="custom-host-container" class="custom-host-container" style="display: none;">
                        <div class="deploy-why-box">
                            <div class="deploy-why-header">
                                <span class="deploy-why-icon">❓</span>
                                <span>Why do you need to input your instance URL?</span>
                            </div>
                            <p class="deploy-why-text">
                                When you fork and deploy this application to Vercel, Vercel assigns your project a unique domain (such as <code>https://my-readme-generator.vercel.app</code>).
                            </p>
                            <p class="deploy-why-text">
                                Your GitHub Profile README needs to know <strong>where</strong> to fetch your live widgets (snake animation, streak stats, language graph, and profile metrics). Entering your Vercel domain below directs all widget image URLs in your README to your dedicated serverless instance, giving you <strong>unlimited personal GitHub API rate limits</strong>, <strong>private repository stats</strong>, and <strong>100% independent uptime</strong>.
                            </p>
                        </div>

                        <div class="deploy-guide-box">
                            <div class="deploy-guide-title">
                                <span>📖</span> Complete Guide: How to Fork &amp; Deploy to Vercel
                            </div>

                            <div class="deploy-steps-list">
                                <div class="deploy-step-item">
                                    <div class="deploy-step-num">1</div>
                                    <div class="deploy-step-content">
                                        <div class="deploy-step-heading">One-Click Fork &amp; Deploy</div>
                                        <div class="deploy-step-desc">
                                            This will automatically fork the repository to your GitHub account and initiate a new Vercel project deployment with predefined settings.
                                        </div>
                                        <div class="deploy-step-actions">
                                            <a href="https://vercel.com/new/clone?repository-url={{ urlencode($repoUrl) }}&env=GITHUB_TOKEN&envDescription=Enter%20your%20GitHub%20Personal%20Access%20Token%20(requires%20read:user,repo%20scopes)%20to%20enable%20stats%20fetching." target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm">
                                                ▲ Deploy to Vercel &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="deploy-step-item">
                                    <div class="deploy-step-num">2</div>
                                    <div class="deploy-step-content">
                                        <div class="deploy-step-heading">Provide Your GitHub Token</div>
                                        <div class="deploy-step-desc">
                                            During the Vercel import process, you'll be prompted for a <code>GITHUB_TOKEN</code>. Paste your GitHub PAT (with <code>read:user</code> and <code>repo</code> scopes) from <a href="https://github.com/settings/tokens" target="_blank" rel="noopener noreferrer" class="link-inline">github.com/settings/tokens</a>. All other environment variables like <code>APP_KEY</code> and <code>ENABLE_API</code> are pre-filled automatically!
                                        </div>
                                    </div>
                                </div>

                                <div class="deploy-step-item">
                                    <div class="deploy-step-num">3</div>
                                    <div class="deploy-step-content">
                                        <div class="deploy-step-heading">Wait for Build &amp; Copy URL</div>
                                        <div class="deploy-step-desc">
                                            Click <strong>Deploy</strong> and wait ~1 minute. When the deployment completes, copy your live project domain URL (e.g. <code>https://my-readme-generator.vercel.app</code>) and paste it below.
                                        </div>
                                    </div>
                                </div>

                                <div class="deploy-step-item">
                                    <div class="deploy-step-num">4</div>
                                    <div class="deploy-step-content">
                                        <div class="deploy-step-heading">Paste &amp; Verify Your Instance URL Below</div>
                                        <div class="deploy-step-desc">
                                            Paste your domain below and click <strong>⚡ Test API</strong>. Once connected, your README automatically routes all widgets to your private instance!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="instance-input-box">
                            <label class="form-label" for="config-host">
                                <span>Your Deployed Vercel App URL</span>
                                <span class="hint">Where your forked app is hosted</span>
                            </label>
                            <div class="host-input-group">
                                <input type="text" id="config-host" class="form-input" placeholder="https://my-readme-widgets.vercel.app" />
                                <button type="button" class="btn btn-secondary" id="test-host-btn" onclick="testCustomHost()">⚡ Test API</button>
                            </div>
                            <div id="host-test-status" class="test-status-text"></div>
                        </div>

                        <div class="deploy-token-note">
                            💡 <strong>About your GitHub token:</strong> Your <code>GITHUB_TOKEN</code> only powers the <code>/api/*</code> endpoints on <em>your own deployment</em>
                            @if ($apiEnabled)
                                — and this instance has the API enabled, so widgets will route through your token once the URL above is connected.
                            @else
                                and requires <code>ENABLE_API=true</code> on that deployment. In community mode widgets ignore the token entirely and load from public CDNs using just your username.
                            @endif
                        </div>
                    </div>

                    <div class="step-footer-nav">
                        <button type="button" class="btn btn-secondary" onclick="switchStep(7)">← Back: Add-ons</button>
                        <button type="button" class="btn btn-success" onclick="copyFullReadme(document.getElementById('copy-btn-header'))">📋 Copy Full README.md</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Right Panel: Sticky Live Preview --}}
        <section class="builder-panel-right">
            <div class="preview-top-bar">
                <div class="preview-tabs">
                    <button type="button" class="preview-tab-btn active" id="tab-btn-preview" onclick="switchPreviewTab('preview')">
                        <span>👁️</span> Live GitHub Render
                    </button>
                    <button type="button" class="preview-tab-btn" id="tab-btn-code" onclick="switchPreviewTab('code')">
                        <span>📄</span> Raw Markdown / HTML Code
                    </button>
                </div>
            </div>

            <div class="preview-stage-scroll">
                <div id="github-rendered-preview" class="github-preview-box"></div>
                <pre class="raw-markdown-area" id="full-readme-code" style="display: none;"></pre>
            </div>
        </section>
    </div>

    {{-- Reset Workspace Confirmation Modal --}}
    <div class="reset-modal-overlay" id="reset-modal" style="display: none;">
        <div class="reset-modal-card">
            <div class="reset-modal-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </div>
            <h2 class="reset-modal-title">Reset Workspace?</h2>
            <p class="reset-modal-desc">This will clear all your current configurations including skills, projects, socials, and theme — and return you to the onboarding screen.</p>
            <div class="reset-modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Cancel</button>
                <button type="button" class="btn" style="background: #ef4444; color: #fff; border: none;" onclick="confirmReset()">Reset Everything</button>
            </div>
        </div>
    </div>

    {{-- Interactive Client Logic --}}
    <script>
        const repoUrl = '{{ $repoUrl }}';
        const savedCustomHost = localStorage.getItem('gh_readme_custom_host') || '';

        // Live online count — heartbeat every 30s, silent on failure.
        (function () {
            function visitorId() {
                try {
                    let id = localStorage.getItem('profilr_visitor_id');
                    if (!id) {
                        id = (crypto.randomUUID ? crypto.randomUUID() : 'v-' + Date.now() + '-' + Math.floor(Math.random() * 1e9));
                        localStorage.setItem('profilr_visitor_id', id);
                    }
                    return id;
                } catch (e) {
                    return '';
                }
            }
            async function pingPresence() {
                try {
                    const res = await fetch('/api/presence?visitor=' + encodeURIComponent(visitorId()), { cache: 'no-store' });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (typeof data.online === 'number') {
                        document.querySelectorAll('[data-online-count]').forEach(el => { el.textContent = data.online; });
                    }
                } catch (e) { /* offline — keep last count */ }
            }
            pingPresence();
            setInterval(pingPresence, 30000);
        })();

        // Theme color palettes (mirrored from ThemeService) used to theme the live preview.
        const THEME_COLORS = @json($themeColors);

        function hex2rgb(hex) {
            hex = hex.replace('#', '');
            if (hex.length === 3) hex = hex.replace(/(.)/g, '$1$1');
            const n = parseInt(hex, 16);
            return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
        }

        function rgb2hex(rgb) {
            return '#' + rgb.map(v => Math.max(0, Math.min(255, Math.round(v))).toString(16).padStart(2, '0')).join('');
        }

        // Blend an overlay color over a base at a given opacity (0-1).
        function blendColors(baseHex, overlayHex, alpha) {
            const base = hex2rgb(baseHex);
            const overlay = hex2rgb(overlayHex);
            return rgb2hex(base.map((b, i) => b * (1 - alpha) + overlay[i] * alpha));
        }

        function getThemeColors(theme) {
            return THEME_COLORS[theme] || THEME_COLORS['light'];
        }

        // Map a theme's palette onto CSS custom properties used across the live preview
        // (project cards, profile info, headings, links, banner frame, blockquote, borders).
        function applyPreviewTheme(theme) {
            const previewEl = document.getElementById('github-rendered-preview');
            if (!previewEl) return;
            const c = getThemeColors(theme);
            const accent = c.accent || c.title;
            const title = c.title || c.accent;
            const s = previewEl.style;
            s.setProperty('--pv-bg', c.bg);
            s.setProperty('--pv-border', c.border);
            s.setProperty('--pv-border-subtle', blendColors(c.bg, c.border, 0.45));
            s.setProperty('--pv-title', title);
            s.setProperty('--pv-text', c.text);
            s.setProperty('--pv-accent', accent);
            s.setProperty('--pv-muted', blendColors(c.bg, c.text, 0.74));
            s.setProperty('--pv-accent-bg', blendColors(c.bg, accent, 0.08));
        }

        const state = {
            currentStep: 1,
            username: '',
            displayName: '',
            bio: '',
            bannerMode: 'preset',
            bannerPreset: 'waving-gradient',
            bannerCustomUrl: '',
            bannerText: '',
            bannerDesc: '',
            bannerAnimation: 'fadeIn',
            bannerHeight: 180,
            typingLines: 'Full-Stack Developer; Laravel & React Enthusiast; Open Source Builder',
            workingOn: 'A GitHub profile README generator',
            learning: 'Cloud infrastructure & DevOps',
            collaborateOn: 'Open source projects',
            helpWith: '',
            askMe: 'Laravel, React, TypeScript',
            reachMe: '',
            projectsUrl: '',
            articlesUrl: '',
            resumeUrl: '',
            funFact: 'I debug with console.log and I\'m proud of it',
            linkedin: 'https://linkedin.com/in/username',
            twitter: 'https://x.com/username',
            youtube: '',
            discord: '',
            medium: '',
            devto: '',
            hashnode: '',
            stackoverflow: '',
            leetcode: '',
            instagram: '',
            twitch: '',
            kaggle: '',
            website: 'https://yourportfolio.dev',
            email: 'dev@example.com',
            buymeacoffee: 'username',
            kofi: '',
            patreon: '',
            paypal: '',
            snake: true,
            visitorCount: true,
            trophies: false,
            quotes: false,
            activeTab: 'preview',
            skills: ['php', 'laravel', 'react', 'tailwind', 'docker', 'mysql'],
            skillsDetected: false,
            detectedForUser: '',
            detectedLanguages: [],
            profileFetchedForUser: '',
            skillsCategoryFilter: 'all',
            skillsSearchQuery: '',
            hostMode: savedCustomHost ? 'custom' : 'community',
            customHost: savedCustomHost,
            theme: 'dark',
            format: 'markdown',
            widgets: {
                stats: true,
                languages: true,
                streak: true
            },
            sectionsOrder: ['bio', 'typing', 'visitor', 'about', 'socials', 'tech', 'projects', 'support', 'snake', 'trophies', 'quotes', 'analytics'],
            socialsOrder: ['linkedin', 'twitter', 'youtube', 'discord', 'medium', 'devto', 'hashnode', 'stackoverflow', 'leetcode', 'instagram', 'twitch', 'kaggle', 'website', 'email'],
            customBio: [],
            customTechBadges: [],
            customSocials: [],
            customSupport: [],
            projects: [
                {
                    title: 'DevPulse - Developer Activity Tracker',
                    techTags: 'React, TypeScript, Laravel, Tailwind CSS',
                    thumbnail: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
                    liveUrl: 'https://devpulse.example.com',
                    repoUrl: 'https://github.com/torvalds/example-project',
                    description: 'An open-source desktop & web analytics suite providing real-time developer productivity metrics.'
                }
            ]
        };

        const bannerPresets = {
            'waving-gradient': { name: 'Wave Gradient', type: 'waving', color: 'gradient', fontColor: 'ffffff' },
            'soft-emerald': { name: 'Soft Emerald', type: 'soft', color: '0:059669,100:10b981', fontColor: 'ffffff' },
            'indigo-violet': { name: 'Indigo Violet', type: 'waving', color: '0:4f46e5,100:9333ea', fontColor: 'ffffff' },
            'sunset-glow': { name: 'Sunset Glow', type: 'wave', color: '0:f43f5e,100:fb923c', fontColor: 'ffffff' },
            'ocean-blue': { name: 'Ocean Blue', type: 'waving', color: '0:0284c7,100:06b6d4', fontColor: 'ffffff' },
            'cyberpunk-slice': { name: 'Cyberpunk Slice', type: 'slice', color: '0:a855f7,100:ec4899', fontColor: 'ffffff' },
            'cylinder-dark': { name: 'Stealth Dark', type: 'cylinder', color: '0:0f172a,100:334155', fontColor: 'ffffff' },
            'minimal-rect': { name: 'Carbon Rect', type: 'rect', color: '0:18181b,100:3f3f46', fontColor: 'ffffff' }
        };

        function getEffectiveBannerUrl() {
            if (state.bannerMode === 'none') return '';
            if (state.bannerMode === 'custom') return state.bannerCustomUrl.trim();

            const preset = bannerPresets[state.bannerPreset] || bannerPresets['waving-gradient'];
            const user = state.username || 'your-username';
            const name = state.bannerText.trim() || state.displayName.trim() || user;
            const fontColor = preset.fontColor || 'ffffff';
            const descParam = state.bannerDesc.trim() ? `&desc=${encodeURIComponent(state.bannerDesc.trim())}&descAlign=center&descAlignY=62&descColor=${fontColor}` : '';
            const animParam = state.bannerAnimation && state.bannerAnimation !== 'none' ? `&animation=${state.bannerAnimation}` : '';

            return `https://capsule-render.vercel.app/api?type=${preset.type}&color=${preset.color}&height=${state.bannerHeight}&section=header&text=${encodeURIComponent(name)}&fontSize=42&fontColor=${fontColor}${descParam}${animParam}`;
        }

        function setBannerMode(mode) {
            state.bannerMode = mode;
            document.querySelectorAll('.banner-pill-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.bannerMode === mode);
            });
            const presetsSection = document.getElementById('banner-presets-section');
            const customSection = document.getElementById('banner-custom-section');
            const previewBox = document.getElementById('banner-live-preview-box');

            if (presetsSection) presetsSection.style.display = mode === 'preset' ? 'block' : 'none';
            if (customSection) customSection.style.display = mode === 'custom' ? 'block' : 'none';
            if (previewBox) previewBox.style.display = mode === 'none' ? 'none' : 'block';

            updateBannerThumbnail();
            updateUI();
        }

        function selectBannerPreset(key) {
            state.bannerPreset = key;
            document.querySelectorAll('.banner-preset-card').forEach(c => {
                c.classList.toggle('active', c.dataset.presetKey === key);
            });
            updateBannerThumbnail();
            updateUI();
        }

        function updateBannerThumbnail() {
            const img = document.getElementById('banner-thumbnail-img');
            const box = document.getElementById('banner-live-preview-box');
            if (!img || !box) return;

            const url = getEffectiveBannerUrl();
            if (url) {
                img.src = url;
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }

        function switchStep(stepNumber) {
            state.currentStep = stepNumber;
            document.querySelectorAll('.step-tab-btn').forEach(btn => {
                btn.classList.toggle('active', parseInt(btn.dataset.step) === stepNumber);
            });
            document.querySelectorAll('.step-pane').forEach(pane => {
                pane.classList.toggle('active', pane.id === `step-pane-${stepNumber}`);
            });

            // Keep the active tab in view within the scrollable step nav
            const activeStepBtn = Array.from(document.querySelectorAll('.step-tab-btn')).find(btn => parseInt(btn.dataset.step) === stepNumber);
            const stepNavBar = document.getElementById('step-nav-bar');
            if (activeStepBtn && stepNavBar) {
                const tabRect = activeStepBtn.getBoundingClientRect();
                const navRect = stepNavBar.getBoundingClientRect();
                const pad = 16;
                if (tabRect.left < navRect.left) stepNavBar.scrollLeft += (tabRect.left - navRect.left) - pad;
                else if (tabRect.right > navRect.right) stepNavBar.scrollLeft += (tabRect.right - navRect.right) + pad;
            }

            // Automatically detect languages from GitHub if the Tech Stack step is selected and not yet detected
            if (stepNumber === 3 && state.username && state.username !== 'your-username' && state.detectedForUser !== state.username) {
                detectTopLanguages(state.username, false);
            }

            saveStateToStorage();
        }

        function getWidgetUrl(key) {
            const user = encodeURIComponent(state.username || 'your-username');
            const theme = encodeURIComponent(state.theme || 'light');

            if (state.hostMode === 'custom' && state.customHost) {
                if (key === 'trophies') {
                    return `${state.customHost}/api/trophies?username=${user}&theme=${theme}&column=6&margin-w=4&no-frame=false&no-bg=false`;
                }
                return `${state.customHost}/api/${key}?username=${user}&theme=${theme}`;
            }

            if (key === 'stats') {
                return `https://github-stats-extended.vercel.app/api?username=${user}&show_icons=true&theme=${theme}&locale=en`;
            }
            if (key === 'languages') {
                return `https://github-stats-extended.vercel.app/api/top-langs/?username=${user}&layout=compact&theme=${theme}`;
            }
            if (key === 'streak') {
                return `https://streak-stats.demolab.com?user=${user}&theme=${theme}`;
            }
            if (key === 'snake') {
                return `https://raw.githubusercontent.com/${user}/${user}/output/github-contribution-grid-snake.svg`;
            }
            if (key === 'trophies') {
                return `https://github-profile-trophy.screw-hand.vercel.app/?username=${user}&theme=radical&no-frame=false&no-bg=false&margin-w=4`;
            }
            return '';
        }

        function getSnippet(key, format = 'markdown') {
            const url = getWidgetUrl(key);
            if (!url) return '';
            const names = {
                stats: 'GitHub Stats',
                languages: 'Top Languages',
                streak: 'GitHub Streak',
                snake: 'Contribution Grid Snake'
            };
            const name = names[key] || 'Widget';
            if (format === 'html') {
                return `<img src="${url}" alt="${name}" />`;
            }
            return `![${name}](${url})`;
        }

        function selectTheme(theme) {
            state.theme = theme;
            document.querySelectorAll('.theme-chip').forEach(chip => {
                chip.classList.toggle('active', chip.dataset.theme === theme);
            });
            applyPreviewTheme(theme);
            updateUI();
        }

        function toggleWidget(key) {
            state.widgets[key] = !state.widgets[key];
            const chip = document.querySelector(`.toggle-chip[data-widget-key="${key}"]`);
            if (chip) {
                chip.classList.toggle('active', state.widgets[key]);
            }
            updateUI();
        }

        const SECTION_LABELS = {
            bio: 'Tagline',
            typing: 'Typing SVG',
            visitor: 'Visitor Count',
            about: 'About Me',
            socials: 'Connect',
            tech: 'Tech Stack',
            projects: 'Projects',
            support: 'Support',
            snake: 'Snake',
            trophies: 'Trophies',
            quotes: 'Quotes',
            analytics: 'Analytics'
        };

        // Add-on-backed sections that can be hidden from the section order strip
        const SECTION_VISIBILITY_TOGGLES = {
            visitor: 'visitorCount',
            snake: 'snake',
            trophies: 'trophies',
            quotes: 'quotes',
        };

        // Section keys that rendered into the live preview on the last render
        let renderedSectionKeys = new Set();

        function normalizeSectionsOrder() {
            const defaults = Object.keys(SECTION_LABELS);
            const saved = Array.isArray(state.sectionsOrder) ? state.sectionsOrder : [];
            const merged = saved.filter(key => defaults.includes(key));
            defaults.forEach(key => {
                if (!merged.includes(key)) merged.push(key);
            });
            state.sectionsOrder = merged;
        }

        function moveSection(key, direction) {
            normalizeSectionsOrder();
            const idx = state.sectionsOrder.indexOf(key);
            if (idx === -1) return;
            const newIdx = idx + direction;
            if (newIdx < 0 || newIdx >= state.sectionsOrder.length) return;
            const item = state.sectionsOrder.splice(idx, 1)[0];
            state.sectionsOrder.splice(newIdx, 0, item);
            updateUI();
        }

        function renderSectionsReorderStrip() {
            normalizeSectionsOrder();
            const strip = document.getElementById('sections-reorder-strip');
            if (!strip) return;

            strip.innerHTML = '';
            const hasRenderBaseline = renderedSectionKeys.size > 0;
            state.sectionsOrder.forEach((key, idx) => {
                const canToggle = Object.prototype.hasOwnProperty.call(SECTION_VISIBILITY_TOGGLES, key);
                const addonKey = SECTION_VISIBILITY_TOGGLES[key];
                const isAddOn = canToggle ? !!state[addonKey] : true;
                const inPreview = hasRenderBaseline ? renderedSectionKeys.has(key) : true;
                const isVisibleInPreview = inPreview && isAddOn;
                const chip = document.createElement('div');
                chip.className = isVisibleInPreview ? 'reorder-chip' : 'reorder-chip reorder-chip-inactive';
                chip.title = isVisibleInPreview
                    ? (SECTION_LABELS[key] || key)
                    : `${SECTION_LABELS[key] || key} — not shown in preview${canToggle ? (isAddOn ? ' (empty)' : ' (hidden — click 👁 to show)') : ' (no content yet)'}`;
                const eyeBtn = canToggle ? `
                    <button type="button" class="reorder-tag-btn reorder-visibility-btn" onclick="toggleSectionVisible('${key}')" title="${isAddOn ? 'Hide' : 'Show'} ${SECTION_LABELS[key] || key}">${isAddOn ? '👁️' : '🚫'}</button>` : '';
                chip.innerHTML = `
                    <button type="button" class="reorder-tag-btn" onclick="moveSection('${key}', -1)" title="Move Earlier" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&larr;</button>${eyeBtn}
                    <span>${SECTION_LABELS[key] || key}</span>
                    <button type="button" class="reorder-tag-btn" onclick="moveSection('${key}', 1)" title="Move Later" ${idx === state.sectionsOrder.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&rarr;</button>
                `;
                strip.appendChild(chip);
            });
        }

        function toggleAddon(key, checked) {
            state[key] = checked;
            const card = document.getElementById(`card-addon-${key === 'visitorCount' ? 'visitor' : key}`);
            if (card) {
                card.classList.toggle('active', checked);
            }
            updateUI();
        }

        function toggleSectionVisible(key) {
            const addonKey = SECTION_VISIBILITY_TOGGLES[key];
            if (!addonKey) return;
            const next = !state[addonKey];
            const chkId = addonKey === 'visitorCount' ? 'addon-visitor-count' : `addon-${addonKey}`;
            const chk = document.getElementById(chkId);
            if (chk) chk.checked = next;
            toggleAddon(addonKey, next);
        }

        // Custom Repeaters & Reordering Logic
        function unlockWorkspace(username) {
            state.username = username;
            const configUser = document.getElementById('config-username');
            if (configUser) configUser.value = username;

            const onboardingInput = document.getElementById('onboarding-username-input');
            if (onboardingInput) onboardingInput.value = username;

            const headerBadge = document.getElementById('header-user-badge');
            const headerText = document.getElementById('header-username-text');
            if (headerBadge && headerText) {
                headerText.textContent = username;
                headerBadge.style.display = 'inline-flex';
            }

            const gate = document.getElementById('onboarding-gate');
            if (gate) gate.style.display = 'none';

            const workspace = document.getElementById('builder-workspace');
            if (workspace) workspace.style.display = 'flex';

            const headerActions = document.getElementById('header-actions');
            if (headerActions) headerActions.style.display = 'flex';

            // Auto-detect most used GitHub languages if not yet detected for this user
            if (username && username !== 'your-username' && state.detectedForUser !== username) {
                detectTopLanguages(username, false);
            }

            // Smart defaults: prefill live preview from the user's real GitHub profile
            if (username && username !== 'your-username' && state.profileFetchedForUser !== username) {
                fetchGitHubProfileSmart(username, false);
            }

            // Pre-populate all input fields with current state defaults
            inputBindings.forEach(([id, prop]) => {
                const el = document.getElementById(id);
                if (el && state[prop]) {
                    el.value = state[prop];
                }
            });

            // Render default projects if present
            if (state.projects.length > 0) {
                renderProjectsList();
            }
        }

        function submitOnboarding() {
            const input = document.getElementById('onboarding-username-input');
            const val = input ? input.value.trim() : '';
            const err = document.getElementById('onboarding-error');
            if (!val) {
                if (err) err.style.display = 'block';
                if (input) input.focus();
                return;
            }
            if (err) err.style.display = 'none';

            unlockWorkspace(val);
            updateBannerThumbnail();
            updateUI();
            saveStateToStorage();

            // Automatically set top programming languages in tech preview from GitHub
            if (state.detectedForUser !== val) {
                detectTopLanguages(val, false);
            }
        }

        function addCustomBioItem(icon = '✨', text = '') {
            state.customBio.push({ icon, text });
            renderCustomBioList();
            updateUI();
        }

        function removeCustomBioItem(index) {
            state.customBio.splice(index, 1);
            renderCustomBioList();
            updateUI();
        }

        function moveCustomBioItem(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.customBio.length) return;
            const item = state.customBio.splice(index, 1)[0];
            state.customBio.splice(newIndex, 0, item);
            renderCustomBioList();
            updateUI();
        }

        function updateCustomBioItem(index, field, value) {
            if (state.customBio[index]) {
                state.customBio[index][field] = value;
                updateUI();
            }
        }

        function renderCustomBioList() {
            const list = document.getElementById('custom-bio-list');
            if (!list) return;
            list.innerHTML = '';
            state.customBio.forEach((item, idx) => {
                const row = document.createElement('div');
                row.className = 'custom-item-row';
                row.innerHTML = `
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomBioItem(${idx}, -1)" title="Move Up" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&uarr;</button>
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomBioItem(${idx}, 1)" title="Move Down" ${idx === state.customBio.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&darr;</button>
                    <input type="text" class="form-input prefix-input" value="${item.icon}" placeholder="Icon" oninput="updateCustomBioItem(${idx}, 'icon', this.value)" title="Emoji or prefix" />
                    <input type="text" class="form-input" style="flex: 1;" value="${item.text}" placeholder="Custom bio point (e.g. Building cool apps with Laravel & React)" oninput="updateCustomBioItem(${idx}, 'text', this.value)" />
                    <button type="button" class="custom-item-delete-btn" onclick="removeCustomBioItem(${idx})" title="Remove item">&times;</button>
                `;
                list.appendChild(row);
            });
        }

        function moveSkill(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.skills.length) return;
            const item = state.skills.splice(index, 1)[0];
            state.skills.splice(newIndex, 0, item);
            updateSkillsUI();
            updateUI();
        }

        function addCustomSkill() {
            const input = document.getElementById('custom-skill-input');
            if (!input) return;
            const rawVal = input.value.trim();
            if (!rawVal) return;

            // If user enters an image URL directly, route to customTechBadges!
            if (rawVal.startsWith('http://') || rawVal.startsWith('https://')) {
                addCustomTechBadge('Custom Tech', '#10b981', '', rawVal);
                input.value = '';
                return;
            }

            const val = rawVal.toLowerCase().replace(/[^a-z0-9_-]/g, '');
            if (!val) return;
            if (!state.skills.includes(val)) {
                state.skills.push(val);
                updateSkillsUI();
                updateUI();
            }
            input.value = '';
        }

        function getCustomTechBadgeImg(b) {
            if (b.imageUrl && b.imageUrl.trim()) {
                const alt = b.name ? b.name.trim() : 'Custom Tech';
                return `<img src="${b.imageUrl.trim()}" height="28" alt="${alt}" onerror="this.style.opacity=0.35;" />`;
            }
            const name = b.name ? b.name.trim() : 'Badge';
            const cleanName = encodeURIComponent(name.replace(/-/g, '--').replace(/_/g, '__'));
            const color = (b.color || '10b981').replace('#', '');
            const logo = b.logo ? b.logo.trim() : (b.name ? b.name.trim().toLowerCase() : '');
            const logoParam = logo ? `&logo=${encodeURIComponent(logo)}&logoColor=white` : '';
            return `<img src="https://img.shields.io/badge/${cleanName}-${color}?style=for-the-badge${logoParam}" alt="${name}" />`;
        }

        function addCustomTechBadge(name = '', color = '#10b981', logo = '', imageUrl = '') {
            state.customTechBadges.push({ name, color: color.replace('#', ''), logo, imageUrl });
            renderCustomTechBadgesList();
            updateUI();
        }

        function removeCustomTechBadge(index) {
            state.customTechBadges.splice(index, 1);
            renderCustomTechBadgesList();
            updateUI();
        }

        function moveCustomTechBadge(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.customTechBadges.length) return;
            const item = state.customTechBadges.splice(index, 1)[0];
            state.customTechBadges.splice(newIndex, 0, item);
            renderCustomTechBadgesList();
            updateUI();
        }

        function updateCustomTechBadge(index, field, value) {
            if (state.customTechBadges[index]) {
                state.customTechBadges[index][field] = field === 'color' ? value.replace('#', '') : value;
                const previewEl = document.getElementById(`custom-badge-preview-${index}`);
                if (previewEl) {
                    previewEl.innerHTML = getCustomTechBadgeImg(state.customTechBadges[index]);
                }
                updateUI();
            }
        }

        function renderCustomTechBadgesList() {
            const list = document.getElementById('custom-tech-badges-list');
            if (!list) return;
            list.innerHTML = '';
            state.customTechBadges.forEach((badge, idx) => {
                const card = document.createElement('div');
                card.className = 'custom-tech-card';
                card.innerHTML = `
                    <div class="custom-tech-header-row">
                        <button type="button" class="reorder-tag-btn" onclick="moveCustomTechBadge(${idx}, -1)" title="Move Up" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&uarr;</button>
                        <button type="button" class="reorder-tag-btn" onclick="moveCustomTechBadge(${idx}, 1)" title="Move Down" ${idx === state.customTechBadges.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&darr;</button>
                        <input type="text" class="form-input" style="flex: 1;" value="${badge.name || ''}" placeholder="Tech Name (e.g. n8n)" oninput="updateCustomTechBadge(${idx}, 'name', this.value)" />
                        <input type="color" class="color-input" value="#${badge.color || '10b981'}" onchange="updateCustomTechBadge(${idx}, 'color', this.value)" title="Badge Color (for Shields)" />
                        <button type="button" class="custom-item-delete-btn" onclick="removeCustomTechBadge(${idx})" title="Remove badge">&times;</button>
                    </div>
                    <div class="custom-tech-inputs-row">
                        <input type="url" class="form-input" style="flex: 1.4;" value="${badge.imageUrl || ''}" placeholder="Direct Image / Icon URL (e.g. https://.../n8n.svg)" oninput="updateCustomTechBadge(${idx}, 'imageUrl', this.value)" title="Paste any SVG/PNG badge or icon link" />
                        <input type="text" class="form-input" style="flex: 1;" value="${badge.logo || ''}" placeholder="Or logo slug (e.g. n8n)" oninput="updateCustomTechBadge(${idx}, 'logo', this.value)" title="Simpleicons slug (used if Image URL is blank)" />
                    </div>
                    <div class="custom-tech-preview-row">
                        <span class="custom-tech-preview-label">Live Preview:</span>
                        <div class="custom-tech-preview-render" id="custom-badge-preview-${idx}">
                            ${getCustomTechBadgeImg(badge)}
                        </div>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function moveSocial(key, direction) {
            const idx = state.socialsOrder.indexOf(key);
            if (idx === -1) return;
            const newIdx = idx + direction;
            if (newIdx < 0 || newIdx >= state.socialsOrder.length) return;
            const item = state.socialsOrder.splice(idx, 1)[0];
            state.socialsOrder.splice(newIdx, 0, item);
            renderSocialsReorderStrip();
            updateUI();
        }

        function renderSocialsReorderStrip() {
            const container = document.getElementById('socials-order-container');
            const strip = document.getElementById('socials-reorder-strip');
            if (!container || !strip) return;

            const activeSocials = state.socialsOrder.filter(k => (state[k] && state[k].trim().length > 0));
            if (activeSocials.length <= 1) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';
            strip.innerHTML = '';
            activeSocials.forEach((k, idx) => {
                const chip = document.createElement('div');
                chip.className = 'reorder-chip';
                const label = k.charAt(0).toUpperCase() + k.slice(1);
                chip.innerHTML = `
                    <button type="button" class="reorder-tag-btn" onclick="moveSocial('${k}', -1)" title="Move Earlier" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&larr;</button>
                    <span>${label}</span>
                    <button type="button" class="reorder-tag-btn" onclick="moveSocial('${k}', 1)" title="Move Later" ${idx === activeSocials.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&rarr;</button>
                `;
                strip.appendChild(chip);
            });
        }

        function addCustomSocial(name = '', url = '', color = '#3b82f6') {
            state.customSocials.push({ name, url, color: color.replace('#', '') });
            renderCustomSocialsList();
            updateUI();
        }

        function removeCustomSocial(index) {
            state.customSocials.splice(index, 1);
            renderCustomSocialsList();
            updateUI();
        }

        function moveCustomSocial(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.customSocials.length) return;
            const item = state.customSocials.splice(index, 1)[0];
            state.customSocials.splice(newIndex, 0, item);
            renderCustomSocialsList();
            updateUI();
        }

        function updateCustomSocial(index, field, value) {
            if (state.customSocials[index]) {
                state.customSocials[index][field] = field === 'color' ? value.replace('#', '') : value;
                updateUI();
            }
        }

        function renderCustomSocialsList() {
            const list = document.getElementById('custom-socials-list');
            if (!list) return;
            list.innerHTML = '';
            state.customSocials.forEach((social, idx) => {
                const row = document.createElement('div');
                row.className = 'custom-item-row';
                row.innerHTML = `
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomSocial(${idx}, -1)" title="Move Up" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&uarr;</button>
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomSocial(${idx}, 1)" title="Move Down" ${idx === state.customSocials.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&darr;</button>
                    <input type="text" class="form-input" style="width: 140px;" value="${social.name}" placeholder="Platform (e.g. Bluesky)" oninput="updateCustomSocial(${idx}, 'name', this.value)" />
                    <input type="url" class="form-input" style="flex: 1;" value="${social.url}" placeholder="Profile URL (e.g. https://bsky.app/profile/...)" oninput="updateCustomSocial(${idx}, 'url', this.value)" />
                    <input type="color" class="color-input" value="#${social.color || '3b82f6'}" onchange="updateCustomSocial(${idx}, 'color', this.value)" title="Badge Color" />
                    <button type="button" class="custom-item-delete-btn" onclick="removeCustomSocial(${idx})" title="Remove social">&times;</button>
                `;
                list.appendChild(row);
            });
        }

        function addCustomSupport(name = '', url = '', color = '#ea4aaa') {
            state.customSupport.push({ name, url, color: color.replace('#', '') });
            renderCustomSupportList();
            updateUI();
        }

        function removeCustomSupport(index) {
            state.customSupport.splice(index, 1);
            renderCustomSupportList();
            updateUI();
        }

        function moveCustomSupport(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.customSupport.length) return;
            const item = state.customSupport.splice(index, 1)[0];
            state.customSupport.splice(newIndex, 0, item);
            renderCustomSupportList();
            updateUI();
        }

        function updateCustomSupport(index, field, value) {
            if (state.customSupport[index]) {
                state.customSupport[index][field] = field === 'color' ? value.replace('#', '') : value;
                updateUI();
            }
        }

        function renderCustomSupportList() {
            const list = document.getElementById('custom-support-list');
            if (!list) return;
            list.innerHTML = '';
            state.customSupport.forEach((sup, idx) => {
                const row = document.createElement('div');
                row.className = 'custom-item-row';
                row.innerHTML = `
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomSupport(${idx}, -1)" title="Move Up" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&uarr;</button>
                    <button type="button" class="reorder-tag-btn" onclick="moveCustomSupport(${idx}, 1)" title="Move Down" ${idx === state.customSupport.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&darr;</button>
                    <input type="text" class="form-input" style="width: 150px;" value="${sup.name}" placeholder="Platform (e.g. GitHub Sponsors)" oninput="updateCustomSupport(${idx}, 'name', this.value)" />
                    <input type="url" class="form-input" style="flex: 1;" value="${sup.url}" placeholder="Donation URL (e.g. https://github.com/sponsors/...)" oninput="updateCustomSupport(${idx}, 'url', this.value)" />
                    <input type="color" class="color-input" value="#${sup.color || 'ea4aaa'}" onchange="updateCustomSupport(${idx}, 'color', this.value)" title="Badge Color" />
                    <button type="button" class="custom-item-delete-btn" onclick="removeCustomSupport(${idx})" title="Remove support">&times;</button>
                `;
                list.appendChild(row);
            });
        }

        function escapeAttr(str) {
            return (str || '').toString().replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function addProject(title = '', description = '', thumbnail = '', liveUrl = '', repoUrl = '', techStack = '') {
            state.projects.push({ title, description, thumbnail, liveUrl, repoUrl, techStack });
            renderProjectsList();
            updateUI();
        }

        function addSampleProject() {
            addProject(
                'DevPulse - Developer Activity Tracker',
                'An open-source desktop & web analytics suite providing real-time developer productivity metrics, GitHub release telemetry, and team activity heatmaps.',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
                'https://devpulse.example.com',
                'https://github.com/torvalds/example-project',
                'React, TypeScript, Laravel, Tailwind CSS'
            );
        }

        function removeProject(index) {
            state.projects.splice(index, 1);
            renderProjectsList();
            updateUI();
        }

        function moveProject(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= state.projects.length) return;
            const item = state.projects.splice(index, 1)[0];
            state.projects.splice(newIndex, 0, item);
            renderProjectsList();
            updateUI();
        }

        function updateProject(index, field, value) {
            if (state.projects[index]) {
                state.projects[index][field] = value;
                if (field === 'thumbnail') {
                    const thumbPreview = document.getElementById(`proj-thumb-preview-${index}`);
                    if (thumbPreview) {
                        const img = thumbPreview.querySelector('img');
                        if (value && value.trim()) {
                            img.src = value.trim();
                            thumbPreview.style.display = 'flex';
                        } else {
                            thumbPreview.style.display = 'none';
                        }
                    }
                }
                updateUI();
            }
        }

        function renderProjectsList() {
            const list = document.getElementById('projects-list');
            const badge = document.getElementById('projects-badge-count');
            if (badge) badge.textContent = state.projects.length;
            if (!list) return;

            if (state.projects.length === 0) {
                list.innerHTML = `
                    <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 13px; border: 1px dashed var(--border); border-radius: 8px;">
                        No featured projects added yet.<br>Click <strong>+ Add Project</strong> or <strong>✨ Add Sample Project</strong> to showcase your work!
                    </div>
                `;
                return;
            }

            list.innerHTML = '';
            state.projects.forEach((proj, idx) => {
                const card = document.createElement('div');
                card.className = 'project-card-item';
                card.innerHTML = `
                    <div class="project-card-header">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div class="project-card-reorder">
                                <button type="button" class="reorder-tag-btn" onclick="moveProject(${idx}, -1)" title="Move Up" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&uarr;</button>
                                <button type="button" class="reorder-tag-btn" onclick="moveProject(${idx}, 1)" title="Move Down" ${idx === state.projects.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&darr;</button>
                            </div>
                            <strong style="font-size: 13px; color: var(--text-primary);">💼 ${proj.title ? escapeAttr(proj.title) : 'Project #' + (idx + 1)}</strong>
                        </div>
                        <button type="button" class="custom-item-delete-btn" onclick="removeProject(${idx})" title="Delete project">&times;</button>
                    </div>
                    <div class="project-card-body">
                        <div class="project-card-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 11px;"><span>Project Title *</span></label>
                                <input type="text" class="form-input" value="${escapeAttr(proj.title)}" placeholder="e.g. My Awesome Web App" oninput="updateProject(${idx}, 'title', this.value)" />
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 11px;"><span>Tech Stack Tags</span></label>
                                <input type="text" class="form-input" value="${escapeAttr(proj.techStack)}" placeholder="e.g. React, Node.js, Tailwind, MongoDB" oninput="updateProject(${idx}, 'techStack', this.value)" />
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 11px;"><span>Thumbnail Image URL</span> <span class="hint">(GitHub raw, Imgur, or hosted image)</span></label>
                            <input type="url" class="form-input" value="${escapeAttr(proj.thumbnail)}" placeholder="https://raw.githubusercontent.com/.../preview.png" oninput="updateProject(${idx}, 'thumbnail', this.value)" />
                            <div id="proj-thumb-preview-${idx}" class="project-thumb-preview" style="${proj.thumbnail && proj.thumbnail.trim() ? 'display: flex;' : 'display: none;'}">
                                <img src="${escapeAttr(proj.thumbnail)}" alt="Thumbnail" onerror="this.parentElement.style.display='none';" />
                                <div class="project-thumb-preview-label">Live Thumbnail Preview</div>
                            </div>
                        </div>

                        <div class="project-card-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 11px;"><span>Live Demo / App URL</span></label>
                                <input type="url" class="form-input" value="${escapeAttr(proj.liveUrl)}" placeholder="https://myapp.vercel.app" oninput="updateProject(${idx}, 'liveUrl', this.value)" />
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 11px;"><span>GitHub Repository URL</span></label>
                                <input type="url" class="form-input" value="${escapeAttr(proj.repoUrl)}" placeholder="https://github.com/username/repo" oninput="updateProject(${idx}, 'repoUrl', this.value)" />
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 11px;"><span>Project Description / Content</span></label>
                            <textarea class="form-input" rows="2" placeholder="Brief summary of features, problem solved, or highlights..." oninput="updateProject(${idx}, 'description', this.value)">${escapeAttr(proj.description)}</textarea>
                        </div>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function toggleSkill(skillId) {
            const index = state.skills.indexOf(skillId);
            if (index > -1) {
                state.skills.splice(index, 1);
            } else {
                state.skills.push(skillId);
            }
            updateSkillsUI();
            updateUI();
        }

        function clearSkills() {
            state.skills = [];
            state.skillsDetected = false;
            updateSkillsUI();
            updateUI();
        }

        const DEFAULT_TYPING_LINES = 'Full-Stack Developer; Laravel & React Enthusiast; Open Source Builder';
        const SAMPLE_PROJECT_TITLE = 'DevPulse - Developer Activity Tracker';

        // Hardcoded demo values are treated as empty by the smart prefill so a
        // real user never ships links to /username or example.com. They remain
        // as fallbacks when GitHub has no data for that field.
        const PLACEHOLDER_VALUES = {
            typingLines: [DEFAULT_TYPING_LINES],
            workingOn: ['A GitHub profile README generator'],
            learning: ['Cloud infrastructure & DevOps'],
            collaborateOn: ['Open source projects'],
            askMe: ['Laravel, React, TypeScript'],
            funFact: ['I debug with console.log and I\'m proud of it'],
            linkedin: ['https://linkedin.com/in/username'],
            twitter: ['https://x.com/username'],
            website: ['https://yourportfolio.dev'],
            email: ['dev@example.com'],
            buymeacoffee: ['username'],
        };

        const SMART_PROP_INPUT_IDS = {
            displayName: 'config-display-name',
            bio: 'config-bio',
            bannerDesc: 'config-banner-desc',
            typingLines: 'config-typing-lines',
            workingOn: 'config-working-on',
            askMe: 'config-ask-me',
            projectsUrl: 'config-projects-url',
            website: 'config-website',
            email: 'config-email',
            linkedin: 'config-linkedin',
            twitter: 'config-twitter',
            buymeacoffee: 'config-buymeacoffee',
        };

        let isFetchingProfile = false;

        function isPlaceholderValue(prop, val) {
            const placeholders = PLACEHOLDER_VALUES[prop];
            if (!placeholders) {
                return false;
            }
            return placeholders.includes((val || '').trim());
        }

        // Fill-empty-only setter: writes the value only when the field is blank
        // or still holds a demo placeholder. Never overwrites user edits.
        function setSmartDefault(prop, value) {
            if (value === undefined || value === null) {
                return false;
            }
            const clean = String(value).trim();
            if (clean === '') {
                return false;
            }
            const current = (state[prop] || '').trim();
            if (current !== '' && !isPlaceholderValue(prop, current)) {
                return false;
            }
            if (current === clean) {
                return false;
            }
            state[prop] = clean;
            const inputId = SMART_PROP_INPUT_IDS[prop];
            const el = inputId ? document.getElementById(inputId) : null;
            if (el) {
                el.value = clean;
            }
            if (prop === 'displayName' || prop === 'bannerDesc') {
                updateBannerThumbnail();
            }
            return true;
        }

        // Turn a GitHub bio into semicolon-separated typing-SVG lines.
        // Falls back to '' so the caller can try top languages instead.
        function buildTypingLinesFromBio(bio) {
            const text = (bio || '').replace(/\s+/g, ' ').trim();
            if (text === '') {
                return '';
            }
            const parts = text
                .split(/\s*[.|•]\s*|\s\|\s|\n+/)
                .map(part => part.trim().replace(/;/g, ','))
                .filter(Boolean);
            const lines = (parts.length > 0 ? parts : [text.replace(/;/g, ',')])
                .slice(0, 3)
                .map(line => (line.length > 42 ? line.slice(0, 39).trimEnd() + '…' : line));
            return lines.join('; ');
        }

        // Typing-lines fallback when the profile bio is empty.
        function buildTypingLinesFromLanguages(langs) {
            const clean = (langs || []).map(lang => (lang || '').trim()).filter(Boolean).slice(0, 3);
            if (clean.length === 0) {
                return '';
            }
            const title = str => str.charAt(0).toUpperCase() + str.slice(1);
            if (clean.length === 1) {
                return `${title(clean[0])} Developer; Open Source Builder`;
            }
            return `${title(clean[0])} Developer; Building with ${title(clean[0])} & ${title(clean[1])}; Open Source Builder`;
        }

        function normalizeBlogUrl(blog) {
            let url = (blog || '').trim();
            if (url === '' || url === '#') {
                return '';
            }
            if (!/^https?:\/\//i.test(url)) {
                url = 'https://' + url;
            }
            try {
                new URL(url);
                return url;
            } catch (err) {
                return '';
            }
        }

        function clearPlaceholderSocials() {
            let cleared = false;
            ['linkedin', 'twitter', 'website', 'email', 'buymeacoffee'].forEach(prop => {
                const current = (state[prop] || '').trim();
                if (current !== '' && isPlaceholderValue(prop, current)) {
                    state[prop] = '';
                    const el = document.getElementById(SMART_PROP_INPUT_IDS[prop]);
                    if (el) {
                        el.value = '';
                    }
                    cleared = true;
                }
            });
            return cleared;
        }

        // Smart defaults: prefill live-preview fields from the user's real public
        // GitHub profile + repositories. Fill-empty-only; demo placeholders count
        // as empty. Silent on failure so hardcoded fallbacks stay intact.
        async function fetchGitHubProfileSmart(username, force = false) {
            const user = (username || state.username || '').trim();
            if (user === '' || user === 'your-username') {
                return;
            }
            if (!force && state.profileFetchedForUser === user) {
                return;
            }
            if (isFetchingProfile) {
                return;
            }
            isFetchingProfile = true;
            try {
                const profileResp = await fetch(`https://api.github.com/users/${encodeURIComponent(user)}`, {
                    headers: { 'Accept': 'application/vnd.github.v3+json' }
                });
                if (!profileResp.ok) {
                    return;
                }
                const profile = await profileResp.json();
                state.profileFetchedForUser = user;

                let changed = false;
                changed = setSmartDefault('displayName', profile.name || profile.login || '') || changed;
                changed = setSmartDefault('bio', profile.bio || '') || changed;
                const shortBio = (profile.bio || '').replace(/\s+/g, ' ').trim().slice(0, 60);
                changed = setSmartDefault('bannerDesc', shortBio) || changed;
                changed = setSmartDefault('website', normalizeBlogUrl(profile.blog)) || changed;
                changed = setSmartDefault('email', profile.email || '') || changed;
                changed = setSmartDefault('projectsUrl', profile.html_url ? `${profile.html_url}?tab=repositories` : '') || changed;

                let repos = [];
                try {
                    const reposResp = await fetch(`https://api.github.com/users/${encodeURIComponent(user)}/repos?sort=pushed&per_page=100`, {
                        headers: { 'Accept': 'application/vnd.github.v3+json' }
                    });
                    if (reposResp.ok) {
                        const data = await reposResp.json();
                        if (Array.isArray(data)) {
                            repos = data;
                        }
                    }
                } catch (err) {
                    console.warn('GitHub repos prefill unavailable:', err);
                }

                if (repos.length > 0) {
                    const ownRepos = repos.filter(repo => !repo.fork);
                    const pool = ownRepos.length > 0 ? ownRepos : repos;

                    if (pool[0] && pool[0].name) {
                        changed = setSmartDefault('workingOn', pool[0].name) || changed;
                    }

                    const primaries = [...new Set(pool.map(repo => (repo.language || '').trim()).filter(Boolean))].slice(0, 3);
                    if (primaries.length > 0) {
                        changed = setSmartDefault('askMe', primaries.join(', ')) || changed;
                    }

                    const isSampleProjects = state.projects.length === 1 && state.projects[0].title === SAMPLE_PROJECT_TITLE;
                    if (isSampleProjects) {
                        const top = [...pool]
                            .sort((a, b) => (b.stargazers_count || 0) - (a.stargazers_count || 0))
                            .slice(0, 4);
                        if (top.length > 0) {
                            state.projects = top.map(repo => ({
                                title: repo.name || 'Featured Project',
                                description: repo.description || '',
                                thumbnail: '',
                                liveUrl: repo.homepage || '',
                                repoUrl: repo.html_url || '',
                                techStack: repo.language || ''
                            }));
                            renderProjectsList();
                            changed = true;
                        }
                    }
                }

                // Typing lines: real bio first, top languages as fallback.
                const bioLines = buildTypingLinesFromBio(profile.bio);
                if (bioLines !== '') {
                    changed = setSmartDefault('typingLines', bioLines) || changed;
                } else if (repos.length > 0) {
                    const pool = repos.filter(repo => !repo.fork).length > 0 ? repos.filter(repo => !repo.fork) : repos;
                    const primaries = [...new Set(pool.map(repo => (repo.language || '').trim()).filter(Boolean))].slice(0, 3);
                    const langLines = buildTypingLinesFromLanguages(primaries);
                    if (langLines !== '') {
                        changed = setSmartDefault('typingLines', langLines) || changed;
                    }
                }

                // Drop demo placeholder links that have no real data behind them.
                changed = clearPlaceholderSocials() || changed;

                if (changed) {
                    updateBannerThumbnail();
                    updateUI();
                } else {
                    saveStateToStorage();
                }
            } catch (err) {
                console.warn('GitHub profile prefill error:', err);
            } finally {
                isFetchingProfile = false;
            }
        }

        const gitHubLangToSkills = {
            'javascript': ['js'],
            'typescript': ['ts'],
            'python': ['py'],
            'php': ['php'],
            'blade': ['laravel', 'php'],
            'html': ['html'],
            'css': ['css'],
            'scss': ['sass'],
            'sass': ['sass'],
            'c': ['c'],
            'c++': ['cpp'],
            'c#': ['cs'],
            'go': ['go'],
            'rust': ['rust'],
            'java': ['java'],
            'kotlin': ['kotlin'],
            'swift': ['swift'],
            'dart': ['dart'],
            'ruby': ['ruby'],
            'vue': ['vue'],
            'svelte': ['svelte'],
            'elixir': ['elixir'],
            'lua': ['lua'],
            'r': ['r'],
            'shell': ['bash'],
            'powershell': ['bash'],
            'dockerfile': ['docker'],
            'graphql': ['graphql'],
            'hcl': ['terraform'],
            'jupyter notebook': ['py']
        };

        let isDetectingLanguages = false;

        async function detectTopLanguages(username, force = false) {
            const user = (username || state.username || '').trim();
            if (!user || user === 'your-username') {
                if (force) alert('Please enter your GitHub username first.');
                return;
            }

            // Skip if already detected for this exact user unless explicitly forced
            if (!force && state.skillsDetected && state.detectedForUser === user) {
                return;
            }

            if (isDetectingLanguages) return;

            const iconEl = document.getElementById('detect-langs-icon');
            const textEl = document.getElementById('detect-langs-text');
            const loaderEl = document.getElementById('skills-detecting-loader');
            const stripImg = document.getElementById('skills-strip-img');
            const emptyHint = document.getElementById('skills-empty-strip-hint');

            isDetectingLanguages = true;
            if (iconEl) iconEl.textContent = '⏳';
            if (textEl) textEl.textContent = 'Detecting...';
            if (loaderEl) loaderEl.style.display = 'flex';
            if (stripImg) stripImg.style.display = 'none';
            if (emptyHint) emptyHint.style.display = 'none';

            try {
                // Prefer the widget's own language breakdown so the detected tech
                // stack always matches the "Top Languages" widget on this host.
                let topLangs = await fetchWidgetTopLanguages(user);

                if (topLangs.length === 0) {
                    topLangs = await fetchGithubLanguages(user);
                }

                if (topLangs.length === 0) {
                    if (force) alert(`Could not detect programming languages from @${user}'s repositories.`);
                    return;
                }

                // Collect mapped skill IDs from top languages
                const detectedSkills = [];
                topLangs.forEach(langName => {
                    const mapped = gitHubLangToSkills[langName.trim().toLowerCase()];
                    if (mapped) {
                        mapped.forEach(skillId => {
                            if (!detectedSkills.includes(skillId) && detectedSkills.length < 8) {
                                detectedSkills.push(skillId);
                            }
                        });
                    }
                });

                if (detectedSkills.length > 0) {

                    state.skills = detectedSkills;
                    state.skillsDetected = true;
                    state.detectedForUser = user;
                    state.detectedLanguages = topLangs.slice(0, 5);

                    // Smart default: no bio → fall back to byte-accurate languages for typing lines.
                    if (!(state.bio || '').trim()) {
                        const langLines = buildTypingLinesFromLanguages(topLangs);
                        if (langLines !== '') {
                            setSmartDefault('typingLines', langLines);
                        }
                    }

                    updateSkillsUI();
                    updateUI();
                    saveStateToStorage();

                    if (textEl) textEl.textContent = 'Auto-Detected!';
                    if (iconEl) iconEl.textContent = '✅';
                    setTimeout(() => {
                        if (textEl) textEl.textContent = 'Re-Detect Languages';
                        if (iconEl) iconEl.textContent = '⚡';
                    }, 3000);
                }
            } catch (err) {
                console.warn('GitHub language auto-detection error:', err);
                if (force) {
                    alert(`Could not fetch GitHub repositories for @${user}. Please ensure the username is correct or select skills manually.`);
                }
            } finally {
                isDetectingLanguages = false;
                if (loaderEl) loaderEl.style.display = 'none';
                if (stripImg && state.skills.length > 0) {
                    stripImg.src = `https://skillicons.dev/icons?i=${state.skills.join(',')}`;
                    stripImg.style.display = 'block';
                } else if (emptyHint && state.skills.length === 0) {
                    emptyHint.style.display = 'block';
                }
                if (!state.skillsDetected && textEl) {
                    textEl.textContent = 'Auto-Detect from My GitHub';
                    if (iconEl) iconEl.textContent = '⚡';
                }
            }
        }

        async function fetchWidgetTopLanguages(user) {
            const host = (state.hostMode === 'custom' && state.customHost) ? state.customHost.replace(/\/+$/, '') : '';
            if (!host) return [];

            try {
                const resp = await fetch(`${host}/api/languages?username=${encodeURIComponent(user)}&limit=8&format=json`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!resp.ok) return [];

                const data = await resp.json();
                if (!Array.isArray(data)) return [];

                return data.map(item => item.name).filter(name => name && name.trim());
            } catch (err) {
                console.warn('Widget language detection unavailable, falling back to GitHub API:', err);
                return [];
            }
        }

        // Byte-based language aggregation (same method as the Top Languages
        // widget) so the auto-detected tech stack matches the widget. A repo's
        // single primary language hides embedded HTML/JS, which is why the
        // strip used to show fewer languages than the widget.
        async function fetchGithubLanguages(user) {
            const resp = await fetch(`https://api.github.com/users/${encodeURIComponent(user)}/repos?sort=pushed&per_page=100`, {
                headers: { 'Accept': 'application/vnd.github.v3+json' }
            });

            if (!resp.ok) {
                throw new Error(`GitHub API HTTP ${resp.status}`);
            }

            const repos = await resp.json();
            if (!Array.isArray(repos) || repos.length === 0) {
                return [];
            }

            const byBytes = await aggregateLanguageBytes(user, repos);
            if (byBytes.length > 0) {
                return byBytes;
            }

            return tallyPrimaryLanguages(repos);
        }

        // Sum language bytes across the user's own recently-pushed repos.
        // Limited to a handful of repos (batched) to stay within the
        // unauthenticated GitHub API rate limit. Returns [] on failure so the
        // caller can fall back to the primary-language tally.
        async function aggregateLanguageBytes(user, repos, maxRepos = 12) {
            const targets = repos.filter(repo => !repo.fork).slice(0, maxRepos);
            if (targets.length === 0) {
                return [];
            }

            // Mirrors NON_CODE_LANGUAGES in GitHubService: not real code.
            const nonCode = new Set([
                'git', 'git config', 'git ignore', 'git links', 'git lfs pointer',
                'markdown', 'text', 'json', 'yaml', 'toml', 'xml', 'csv', 'tsv',
                'ini', 'properties', 'bibtex', 'restructuredtext', 'org',
                'textile', 'rdoc', 'creole', 'mediawiki', 'asciidoc', 'editorconfig'
            ]);

            const totals = {};
            for (let i = 0; i < targets.length; i += 4) {
                const batch = targets.slice(i, i + 4);
                const results = await Promise.allSettled(batch.map(repo =>
                    fetch(`https://api.github.com/repos/${encodeURIComponent(user)}/${encodeURIComponent(repo.name)}/languages`, {
                        headers: { 'Accept': 'application/vnd.github.v3+json' }
                    }).then(res => (res.ok ? res.json() : {}))
                ));
                results.forEach(result => {
                    if (result.status !== 'fulfilled' || !result.value) {
                        return;
                    }
                    Object.entries(result.value).forEach(([lang, bytes]) => {
                        const key = (lang || '').trim().toLowerCase();
                        if (!key || nonCode.has(key)) {
                            return;
                        }
                        totals[key] = (totals[key] || 0) + bytes;
                    });
                });
            }

            return Object.keys(totals).sort((a, b) => totals[b] - totals[a]);
        }

        // Fallback: tally each repo's primary language weighted by stars & activity.
        function tallyPrimaryLanguages(repos) {
            const langScores = {};
            repos.forEach(repo => {
                const lang = repo.language;
                if (lang && lang.trim()) {
                    const key = lang.trim().toLowerCase();
                    const stars = repo.stargazers_count || 0;
                    const weight = (repo.fork ? 0.5 : 1.0) * (1 + Math.log2(stars + 1));
                    langScores[key] = (langScores[key] || 0) + weight;
                }
            });

            // Fallback if only forks were found
            if (Object.keys(langScores).length === 0) {
                repos.forEach(repo => {
                    const lang = repo.language;
                    if (lang && lang.trim()) {
                        const key = lang.trim().toLowerCase();
                        langScores[key] = (langScores[key] || 0) + 1;
                    }
                });
            }

            return Object.keys(langScores).sort((a, b) => langScores[b] - langScores[a]);
        }

        function updateSkillsUI() {
            document.querySelectorAll('.skill-chip').forEach(chip => {
                const id = chip.dataset.skillId;
                chip.classList.toggle('selected', state.skills.includes(id));
            });

            const badge = document.getElementById('skills-badge-count');
            if (badge) badge.textContent = state.skills.length;

            const selectedPillCount = document.getElementById('skills-selected-pill-count');
            if (selectedPillCount) selectedPillCount.textContent = state.skills.length;

            const tagsContainer = document.getElementById('selected-skills-tags');
            if (tagsContainer) {
                tagsContainer.innerHTML = '';
                state.skills.forEach((id, idx) => {
                    const chip = document.querySelector(`.skill-chip[data-skill-id="${id}"]`);
                    const name = chip ? chip.dataset.skillName : id;
                    const tag = document.createElement('span');
                    tag.className = 'selected-skill-tag';
                    tag.innerHTML = `
                        <button type="button" class="reorder-tag-btn" onclick="moveSkill(${idx}, -1)" title="Move Left" ${idx === 0 ? 'disabled style="opacity:0.3"' : ''}>&larr;</button>
                        <span>${name}</span>
                        <button type="button" class="reorder-tag-btn" onclick="moveSkill(${idx}, 1)" title="Move Right" ${idx === state.skills.length - 1 ? 'disabled style="opacity:0.3"' : ''}>&rarr;</button>
                        <button type="button" onclick="toggleSkill('${id}')" title="Remove">&times;</button>
                    `;
                    tagsContainer.appendChild(tag);
                });
            }

            const stripImg = document.getElementById('skills-strip-img');
            const emptyHint = document.getElementById('skills-empty-strip-hint');
            if (stripImg) {
                if (state.skills.length > 0) {
                    stripImg.src = `https://skillicons.dev/icons?i=${state.skills.join(',')}`;
                    stripImg.style.display = 'block';
                    if (emptyHint) emptyHint.style.display = 'none';
                } else {
                    stripImg.style.display = 'none';
                    if (emptyHint) emptyHint.style.display = 'block';
                }
            }

            const detectedBadge = document.getElementById('skills-detected-badge');
            if (detectedBadge) {
                if (state.skillsDetected && state.skills && state.skills.length > 0) {
                    const skillNames = state.skills.map(id => {
                        const chip = document.querySelector(`.skill-chip[data-skill-id="${id}"]`);
                        return chip ? chip.dataset.skillName : id;
                    });
                    detectedBadge.textContent = `⚡ Auto-detected: ${skillNames.join(', ')}`;
                    detectedBadge.style.display = 'inline-block';
                } else {
                    detectedBadge.style.display = 'none';
                }
            }

            if (state.skillsCategoryFilter === 'selected') {
                applySkillsFilter();
            }
        }

        function onSkillsSearchInput(val) {
            state.skillsSearchQuery = val;
            const clearBtn = document.getElementById('skills-search-clear-btn');
            if (clearBtn) {
                clearBtn.style.display = val.trim() ? 'block' : 'none';
            }
            applySkillsFilter();
        }

        function clearSkillsSearch() {
            const input = document.getElementById('skills-search-input');
            if (input) input.value = '';
            state.skillsSearchQuery = '';
            const clearBtn = document.getElementById('skills-search-clear-btn');
            if (clearBtn) clearBtn.style.display = 'none';
            applySkillsFilter();
            if (input) input.focus();
        }

        function setSkillCategoryFilter(category) {
            state.skillsCategoryFilter = category;
            document.querySelectorAll('.skills-pill-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === category);
            });
            applySkillsFilter();
        }

        function applySkillsFilter() {
            const q = (state.skillsSearchQuery || '').trim().toLowerCase();
            const cat = state.skillsCategoryFilter || 'all';
            let totalMatches = 0;
            let totalAvailable = 0;

            document.querySelectorAll('.skills-category-group').forEach(group => {
                const groupCategory = group.dataset.category;
                const matchesCategory = (cat === 'all' || cat === 'selected' || groupCategory === cat);

                let visibleInGroup = 0;
                group.querySelectorAll('.skill-chip').forEach(chip => {
                    totalAvailable++;
                    const id = chip.dataset.skillId.toLowerCase();
                    const name = chip.dataset.skillName.toLowerCase();
                    const isSelected = state.skills.includes(chip.dataset.skillId);

                    let visible = false;
                    if (cat === 'selected') {
                        visible = isSelected && (q === '' || id.includes(q) || name.includes(q));
                    } else if (matchesCategory) {
                        visible = (q === '' || id.includes(q) || name.includes(q));
                    }

                    chip.style.display = visible ? 'inline-flex' : 'none';
                    if (visible) {
                        visibleInGroup++;
                        totalMatches++;
                    }
                });

                group.style.display = visibleInGroup > 0 ? 'block' : 'none';
            });

            // Update match count badge
            const countBadge = document.getElementById('skills-search-count-badge');
            if (countBadge) {
                if (q !== '' || cat !== 'all') {
                    countBadge.textContent = `${totalMatches} ${totalMatches === 1 ? 'skill' : 'skills'}`;
                    countBadge.style.display = 'inline-block';
                } else {
                    countBadge.textContent = `${totalAvailable} skills`;
                    countBadge.style.display = 'inline-block';
                }
            }

            // Update empty state
            const emptyState = document.getElementById('skills-empty-state');
            const emptyQuery = document.getElementById('skills-empty-query');
            if (emptyState) {
                if (totalMatches === 0) {
                    emptyState.style.display = 'block';
                    if (emptyQuery) emptyQuery.textContent = q || cat;
                } else {
                    emptyState.style.display = 'none';
                }
            }
        }

        function useQueryAsCustomSkill() {
            const q = (state.skillsSearchQuery || '').trim();
            if (q) {
                const customInput = document.getElementById('custom-skill-input');
                if (customInput) {
                    customInput.value = q;
                    addCustomSkill();
                    clearSkillsSearch();
                }
            }
        }

        function switchPreviewTab(tab) {
            state.activeTab = tab;
            const btnPreview = document.getElementById('tab-btn-preview');
            const btnCode = document.getElementById('tab-btn-code');
            const boxPreview = document.getElementById('github-rendered-preview');
            const boxCode = document.getElementById('full-readme-code');

            if (tab === 'preview') {
                btnPreview.classList.add('active');
                btnCode.classList.remove('active');
                boxPreview.style.display = 'block';
                boxCode.style.display = 'none';
            } else {
                btnPreview.classList.remove('active');
                btnCode.classList.add('active');
                boxPreview.style.display = 'none';
                boxCode.style.display = 'block';
            }
        }

        function generateFullReadme() {
            const user = state.username || 'your-username';
            const name = state.displayName.trim() || user;
            const headerLines = [];
            const sections = {};

            // Banner Image
            const bannerUrl = getEffectiveBannerUrl();
            if (bannerUrl) {
                headerLines.push('<p align="center">');
                headerLines.push(`  <img src="${bannerUrl}" alt="${name} Banner" />`);
                headerLines.push('</p>\n');
            }

            // Title & Greeting
            headerLines.push(`# Hi there, I'm ${name} 👋\n`);

            // Tagline / Subtitle
            if (state.bio.trim()) {
                sections.bio = [`> ${state.bio.trim()}\n`];
            }

            // Typing SVG Intro
            if (state.typingLines.trim()) {
                sections.typing = [
                    '<p align="center">',
                    `  <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&pause=1000&color=22C55E&center=true&vCenter=true&width=435&lines=${encodeURIComponent(state.typingLines.trim())}" alt="Typing SVG" />`,
                    '</p>\n'
                ];
            }

            // Visitor Count Badge
            if (state.visitorCount) {
                sections.visitor = [
                    '<p align="left">',
                    `  <img src="https://komarev.com/ghpvc/?username=${encodeURIComponent(user)}&label=Profile%20Views&color=0e75b6&style=flat" alt="${user} Profile Views" />`,
                    '</p>\n'
                ];
            }

            // About Me
            const aboutLines = [];
            if (state.workingOn.trim()) aboutLines.push(`- 🔭 I'm currently working on **${state.workingOn.trim()}**`);
            if (state.learning.trim()) aboutLines.push(`- 🌱 I'm currently learning **${state.learning.trim()}**`);
            if (state.collaborateOn.trim()) aboutLines.push(`- 👯 I'm looking to collaborate on **${state.collaborateOn.trim()}**`);
            if (state.helpWith.trim()) aboutLines.push(`- 🤝 I'm looking for help with **${state.helpWith.trim()}**`);
            if (state.askMe.trim()) aboutLines.push(`- 💬 Ask me about **${state.askMe.trim()}**`);
            if (state.reachMe.trim()) aboutLines.push(`- 📫 How to reach me: **${state.reachMe.trim()}**`);
            if (state.projectsUrl.trim()) aboutLines.push(`- 👨‍💻 All of my projects are at [${state.projectsUrl.trim()}](${state.projectsUrl.trim()})`);
            if (state.articlesUrl.trim()) aboutLines.push(`- 📝 I regularly write articles on [${state.articlesUrl.trim()}](${state.articlesUrl.trim()})`);
            if (state.resumeUrl.trim()) aboutLines.push(`- 📄 Know about my experiences [Read My Resume](${state.resumeUrl.trim()})`);
            if (state.funFact.trim()) aboutLines.push(`- ⚡ Fun fact: **${state.funFact.trim()}**`);

            if (aboutLines.length > 0 || state.customBio.length > 0) {
                state.customBio.forEach(item => {
                    if ((item.text || '').trim()) {
                        aboutLines.push(`- ${item.icon ? item.icon + ' ' : ''}${(item.text || '').trim()}`);
                    }
                });
                sections.about = ['### 🚀 About Me', aboutLines.join('\n'), ''];
            }

            // Social Badges
            const socialBadges = [];
            const presetSocialBadges = {
                linkedin: () => state.linkedin.trim() ? `<a href="${state.linkedin.trim()}" target="_blank"><img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn" /></a>` : '',
                twitter: () => state.twitter.trim() ? `<a href="${state.twitter.trim()}" target="_blank"><img src="https://img.shields.io/badge/X-000000?style=for-the-badge&logo=x&logoColor=white" alt="X" /></a>` : '',
                youtube: () => state.youtube.trim() ? `<a href="${state.youtube.trim()}" target="_blank"><img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" alt="YouTube" /></a>` : '',
                discord: () => state.discord.trim() ? `<a href="${state.discord.trim()}" target="_blank"><img src="https://img.shields.io/badge/Discord-7289DA?style=for-the-badge&logo=discord&logoColor=white" alt="Discord" /></a>` : '',
                medium: () => state.medium.trim() ? `<a href="${state.medium.trim()}" target="_blank"><img src="https://img.shields.io/badge/Medium-12100E?style=for-the-badge&logo=medium&logoColor=white" alt="Medium" /></a>` : '',
                devto: () => state.devto.trim() ? `<a href="${state.devto.trim()}" target="_blank"><img src="https://img.shields.io/badge/DEV.to-0A0A0A?style=for-the-badge&logo=devdotto&logoColor=white" alt="DEV.to" /></a>` : '',
                hashnode: () => state.hashnode.trim() ? `<a href="${state.hashnode.trim()}" target="_blank"><img src="https://img.shields.io/badge/Hashnode-2962FF?style=for-the-badge&logo=hashnode&logoColor=white" alt="Hashnode" /></a>` : '',
                stackoverflow: () => state.stackoverflow.trim() ? `<a href="${state.stackoverflow.trim()}" target="_blank"><img src="https://img.shields.io/badge/Stack_Overflow-FE7A16?style=for-the-badge&logo=stack-overflow&logoColor=white" alt="Stack Overflow" /></a>` : '',
                leetcode: () => state.leetcode.trim() ? `<a href="${state.leetcode.trim()}" target="_blank"><img src="https://img.shields.io/badge/LeetCode-FFA116?style=for-the-badge&logo=leetcode&logoColor=black" alt="LeetCode" /></a>` : '',
                instagram: () => state.instagram.trim() ? `<a href="${state.instagram.trim()}" target="_blank"><img src="https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white" alt="Instagram" /></a>` : '',
                twitch: () => state.twitch.trim() ? `<a href="${state.twitch.trim()}" target="_blank"><img src="https://img.shields.io/badge/Twitch-9146FF?style=for-the-badge&logo=twitch&logoColor=white" alt="Twitch" /></a>` : '',
                kaggle: () => state.kaggle.trim() ? `<a href="${state.kaggle.trim()}" target="_blank"><img src="https://img.shields.io/badge/Kaggle-20BEFF?style=for-the-badge&logo=kaggle&logoColor=white" alt="Kaggle" /></a>` : '',
                website: () => state.website.trim() ? `<a href="${state.website.trim()}" target="_blank"><img src="https://img.shields.io/badge/Website-4B32C3?style=for-the-badge&logo=google-chrome&logoColor=white" alt="Website" /></a>` : '',
                email: () => state.email.trim() ? `<a href="mailto:${state.email.trim()}" target="_blank"><img src="https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white" alt="Email" /></a>` : '',
            };

            state.socialsOrder.forEach(key => {
                if (presetSocialBadges[key]) {
                    const badge = presetSocialBadges[key]();
                    if (badge) socialBadges.push(badge);
                }
            });

            state.customSocials.forEach(s => {
                if ((s.name || '').trim() && (s.url || '').trim()) {
                    const cleanName = encodeURIComponent((s.name || '').trim().replace(/-/g, '--').replace(/_/g, '__'));
                    const logoSlug = encodeURIComponent((s.name || '').trim().toLowerCase().replace(/[^a-z0-9]/g, ''));
                    socialBadges.push(`<a href="${s.url.trim()}" target="_blank"><img src="https://img.shields.io/badge/${cleanName}-${s.color || '3b82f6'}?style=for-the-badge&logo=${logoSlug}&logoColor=white" alt="${s.name}" /></a>`);
                }
            });

            if (socialBadges.length > 0) {
                sections.socials = ['### 🌐 Connect with Me', '<p align="left">', ...socialBadges.map(badge => `  ${badge}`), '</p>\n'];
            }

            // Tech Stack
            const techBadges = [];
            if (state.skills.length > 0) {
                techBadges.push(`<img src="https://skillicons.dev/icons?i=${state.skills.join(',')}" alt="My Skills" />`);
            }
            state.customTechBadges.forEach(b => {
                if ((b.name && b.name.trim()) || (b.imageUrl && b.imageUrl.trim())) {
                    techBadges.push(getCustomTechBadgeImg(b));
                }
            });
            if (techBadges.length > 0) {
                sections.tech = ['### 🛠️ Tech Stack & Skills', '<p align="left">', ...techBadges.map(tb => `  ${tb}`), '</p>\n'];
            }

            // Featured Projects
            const validProjects = state.projects.filter(p => (p.title && p.title.trim()) || (p.description && p.description.trim()) || (p.thumbnail && p.thumbnail.trim()));
            if (validProjects.length > 0) {
                const projectLines = ['### 💼 Featured Projects\n', '<table>'];
                for (let i = 0; i < validProjects.length; i += 2) {
                    projectLines.push('  <tr>');
                    for (let j = i; j < Math.min(i + 2, validProjects.length); j++) {
                        const proj = validProjects[j];
                        const title = (proj.title || '').trim() || 'Featured Project';
                        const desc = (proj.description || '').trim();
                        const thumb = (proj.thumbnail || '').trim();
                        const live = (proj.liveUrl || '').trim();
                        const repo = (proj.repoUrl || '').trim();
                        const tech = (proj.techStack || '').trim();
                        const link = live || repo || '#';

                        projectLines.push('    <td width="50%" valign="top">');
                        projectLines.push(`      <h4 align="center"><a href="${link}"><b>${title}</b></a></h4>`);
                        if (thumb) {
                            projectLines.push(`      <a href="${link}">`);
                            projectLines.push(`        <img src="${thumb}" alt="${title}" width="100%" />`);
                            projectLines.push('      </a>');
                        }
                        if (desc) {
                            projectLines.push(`      <p>${desc}</p>`);
                        }
                        if (tech) {
                            projectLines.push(`      <p><strong>Tech Stack:</strong> ${tech}</p>`);
                        }
                        const links = [];
                        if (repo) links.push(`<a href="${repo}"><b>📂 GitHub</b></a>`);
                        if (live) links.push(`<a href="${live}"><b>🚀 Live Demo</b></a>`);
                        if (links.length > 0) {
                            projectLines.push(`      <p align="center">${links.join(' &bull; ')}</p>`);
                        }
                        projectLines.push('    </td>');
                    }
                    if (validProjects.length % 2 !== 0 && i === validProjects.length - 1) {
                        projectLines.push('    <td width="50%" valign="top"></td>');
                    }
                    projectLines.push('  </tr>');
                }
                projectLines.push('</table>\n');
                sections.projects = projectLines;
            }

            // Support Me
            const supportBadges = [];
            if (state.buymeacoffee.trim()) supportBadges.push(`<a href="https://buymeacoffee.com/${state.buymeacoffee.trim()}" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="36" alt="Buy Me A Coffee" /></a>`);
            if (state.kofi.trim()) supportBadges.push(`<a href="https://ko-fi.com/${state.kofi.trim()}" target="_blank"><img src="https://ko-fi.com/img/githubbutton_sm.svg" height="36" alt="Support on Ko-fi" /></a>`);
            if (state.patreon.trim()) supportBadges.push(`<a href="https://patreon.com/${state.patreon.trim()}" target="_blank"><img src="https://img.shields.io/badge/Patreon-F96854?style=for-the-badge&logo=patreon&logoColor=white" alt="Patreon" /></a>`);
            if (state.paypal.trim()) supportBadges.push(`<a href="https://paypal.me/${state.paypal.trim()}" target="_blank"><img src="https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="PayPal" /></a>`);

            state.customSupport.forEach(sp => {
                if ((sp.name || '').trim() && (sp.url || '').trim()) {
                    const cleanName = encodeURIComponent((sp.name || '').trim().replace(/-/g, '--').replace(/_/g, '__'));
                    const logoSlug = encodeURIComponent((sp.name || '').trim().toLowerCase().replace(/[^a-z0-9]/g, ''));
                    supportBadges.push(`<a href="${sp.url.trim()}" target="_blank"><img src="https://img.shields.io/badge/${cleanName}-${sp.color || 'ea4aaa'}?style=for-the-badge&logo=${logoSlug}&logoColor=white" alt="${sp.name}" /></a>`);
                }
            });

            if (supportBadges.length > 0) {
                sections.support = ['### ☕ Support Me', '<p align="left">', ...supportBadges.map(b => `  ${b}`), '</p>\n'];
            }

            // Snake Widget
            if (state.snake) {
                const snakeLines = ['### 🐍 Contribution Graph Snake Animation'];
                if (state.hostMode === 'custom' && state.customHost) {
                    snakeLines.push('<p align="center">');
                    snakeLines.push(`  <img src="${state.customHost}/api/snake?username=${encodeURIComponent(user)}&theme=${encodeURIComponent(state.theme)}" alt="GitHub Contribution Snake" />`);
                    snakeLines.push('</p>\n');
                } else {
                    snakeLines.push('<picture>');
                    snakeLines.push(`  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/${encodeURIComponent(user)}/${encodeURIComponent(user)}/output/github-contribution-grid-snake-dark.svg">`);
                    snakeLines.push(`  <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/${encodeURIComponent(user)}/${encodeURIComponent(user)}/output/github-contribution-grid-snake.svg">`);
                    snakeLines.push(`  <img alt="GitHub Contribution Snake" src="https://raw.githubusercontent.com/${encodeURIComponent(user)}/${encodeURIComponent(user)}/output/github-contribution-grid-snake.svg" />`);
                    snakeLines.push('</picture>\n');
                }
                sections.snake = snakeLines;
            }

            // Trophies — own instance when Vercel hosting is selected (avoids third-party rate limits)
            if (state.trophies) {
                const trophyLines = ['### 🏆 GitHub Trophies', '<p align="center">'];
                if (state.hostMode === 'custom' && state.customHost) {
                    trophyLines.push(`  <img src="${state.customHost}/api/trophies?username=${encodeURIComponent(user)}&theme=${encodeURIComponent(state.theme)}&column=6&margin-w=4&no-frame=false&no-bg=false" alt="GitHub Trophies" />`);
                } else {
                    trophyLines.push(`  <img src="https://github-profile-trophy.screw-hand.vercel.app/?username=${encodeURIComponent(user)}&theme=radical&no-frame=false&no-bg=false&margin-w=4" alt="GitHub Trophies" />`);
                }
                trophyLines.push('</p>\n');
                sections.trophies = trophyLines;
            }

            // Quotes
            if (state.quotes) {
                sections.quotes = [
                    '### 💬 Random Dev Quote',
                    '<p align="center">',
                    '  <img src="https://quotes-github-readme.vercel.app/api?type=horizontal&theme=radical" alt="Dev Quote" />',
                    '</p>\n'
                ];
            }

            // Analytics Widgets
            const hasWidgets = state.widgets.stats || state.widgets.languages || state.widgets.streak;
            if (hasWidgets) {
                const analyticsLines = ['## 📊 GitHub Analytics\n'];
                if (state.widgets.stats) {
                    analyticsLines.push('<p align="center">', `  ${getSnippet('stats', 'html')}`, '</p>\n');
                }
                if (state.widgets.languages) {
                    analyticsLines.push('<p align="center">', `  ${getSnippet('languages', 'html')}`, '</p>\n');
                }
                if (state.widgets.streak) {
                    analyticsLines.push('<p align="center">', `  ${getSnippet('streak', 'html')}`, '</p>\n');
                }
                sections.analytics = analyticsLines;
            }

            normalizeSectionsOrder();
            const lines = [...headerLines];
            state.sectionsOrder.forEach(key => {
                if (sections[key] && sections[key].length > 0) {
                    lines.push(...sections[key]);
                }
            });

            lines.push('---\n');
            lines.push('<p align="center">');
            lines.push(`  <i>Generated with <a href="${repoUrl}">GitHub Profile README Generator</a></i>`);
            lines.push('</p>');

            return lines.join('\n');
        }

        function renderGithubPreview() {
            const previewEl = document.getElementById('github-rendered-preview');
            if (!previewEl) return;

            const user = state.username || 'your-username';
            const name = state.displayName.trim() || user;
            let headerHtml = '';
            const sections = {};

            const bannerUrl = getEffectiveBannerUrl();
            if (bannerUrl) {
                headerHtml += `<p style="text-align: center;"><img src="${bannerUrl}" alt="Banner" style="max-height: 220px; width: 100%; object-fit: cover; border-radius: 8px;" /></p>`;
            }

            headerHtml += `<h1>Hi there, I'm ${name} 👋</h1>`;

            if (state.bio.trim()) {
                sections.bio = `<blockquote>${state.bio.trim()}</blockquote>`;
            }

            if (state.typingLines.trim()) {
                sections.typing = `<p style="text-align: center;"><img src="https://readme-typing-svg.demolab.com?font=Fira+Code&pause=1000&color=22C55E&center=true&vCenter=true&width=435&lines=${encodeURIComponent(state.typingLines.trim())}" alt="Typing SVG" /></p>`;
            }

            if (state.visitorCount) {
                sections.visitor = `<p><img src="https://komarev.com/ghpvc/?username=${encodeURIComponent(user)}&label=Profile%20Views&color=0e75b6&style=flat" alt="Views" /></p>`;
            }

            const aboutItems = [];
            if (state.workingOn.trim()) aboutItems.push(`🔭 I'm currently working on <strong>${state.workingOn.trim()}</strong>`);
            if (state.learning.trim()) aboutItems.push(`🌱 I'm currently learning <strong>${state.learning.trim()}</strong>`);
            if (state.collaborateOn.trim()) aboutItems.push(`👯 I'm looking to collaborate on <strong>${state.collaborateOn.trim()}</strong>`);
            if (state.helpWith.trim()) aboutItems.push(`🤝 I'm looking for help with <strong>${state.helpWith.trim()}</strong>`);
            if (state.askMe.trim()) aboutItems.push(`💬 Ask me about <strong>${state.askMe.trim()}</strong>`);
            if (state.reachMe.trim()) aboutItems.push(`📫 How to reach me: <strong>${state.reachMe.trim()}</strong>`);
            if (state.projectsUrl.trim()) aboutItems.push(`👨‍💻 All of my projects are at <a href="${state.projectsUrl.trim()}" target="_blank">${state.projectsUrl.trim()}</a>`);
            if (state.articlesUrl.trim()) aboutItems.push(`📝 I regularly write articles on <a href="${state.articlesUrl.trim()}" target="_blank">${state.articlesUrl.trim()}</a>`);
            if (state.resumeUrl.trim()) aboutItems.push(`📄 Know about my experiences <a href="${state.resumeUrl.trim()}" target="_blank">Read My Resume</a>`);
            if (state.funFact.trim()) aboutItems.push(`⚡ Fun fact: <strong>${state.funFact.trim()}</strong>`);

            if (aboutItems.length > 0 || state.customBio.length > 0) {
                state.customBio.forEach(item => {
                    if ((item.text || '').trim()) {
                        aboutItems.push(`${item.icon ? item.icon + ' ' : ''}<strong>${(item.text || '').trim()}</strong>`);
                    }
                });
                sections.about = `<h3>🚀 About Me</h3><ul>${aboutItems.map(item => `<li>${item}</li>`).join('')}</ul>`;
            }

            // Socials
            const badges = [];
            const previewPresetSocials = {
                linkedin: () => state.linkedin.trim() ? `<a href="${state.linkedin.trim()}" target="_blank"><img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" /></a>` : '',
                twitter: () => state.twitter.trim() ? `<a href="${state.twitter.trim()}" target="_blank"><img src="https://img.shields.io/badge/X-000000?style=for-the-badge&logo=x&logoColor=white" /></a>` : '',
                youtube: () => state.youtube.trim() ? `<a href="${state.youtube.trim()}" target="_blank"><img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" /></a>` : '',
                discord: () => state.discord.trim() ? `<a href="${state.discord.trim()}" target="_blank"><img src="https://img.shields.io/badge/Discord-7289DA?style=for-the-badge&logo=discord&logoColor=white" /></a>` : '',
                medium: () => state.medium.trim() ? `<a href="${state.medium.trim()}" target="_blank"><img src="https://img.shields.io/badge/Medium-12100E?style=for-the-badge&logo=medium&logoColor=white" /></a>` : '',
                devto: () => state.devto.trim() ? `<a href="${state.devto.trim()}" target="_blank"><img src="https://img.shields.io/badge/DEV.to-0A0A0A?style=for-the-badge&logo=devdotto&logoColor=white" /></a>` : '',
                hashnode: () => state.hashnode.trim() ? `<a href="${state.hashnode.trim()}" target="_blank"><img src="https://img.shields.io/badge/Hashnode-2962FF?style=for-the-badge&logo=hashnode&logoColor=white" /></a>` : '',
                stackoverflow: () => state.stackoverflow.trim() ? `<a href="${state.stackoverflow.trim()}" target="_blank"><img src="https://img.shields.io/badge/Stack_Overflow-FE7A16?style=for-the-badge&logo=stack-overflow&logoColor=white" /></a>` : '',
                leetcode: () => state.leetcode.trim() ? `<a href="${state.leetcode.trim()}" target="_blank"><img src="https://img.shields.io/badge/LeetCode-FFA116?style=for-the-badge&logo=leetcode&logoColor=black" /></a>` : '',
                instagram: () => state.instagram.trim() ? `<a href="${state.instagram.trim()}" target="_blank"><img src="https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white" /></a>` : '',
                twitch: () => state.twitch.trim() ? `<a href="${state.twitch.trim()}" target="_blank"><img src="https://img.shields.io/badge/Twitch-9146FF?style=for-the-badge&logo=twitch&logoColor=white" /></a>` : '',
                kaggle: () => state.kaggle.trim() ? `<a href="${state.kaggle.trim()}" target="_blank"><img src="https://img.shields.io/badge/Kaggle-20BEFF?style=for-the-badge&logo=kaggle&logoColor=white" /></a>` : '',
                website: () => state.website.trim() ? `<a href="${state.website.trim()}" target="_blank"><img src="https://img.shields.io/badge/Website-4B32C3?style=for-the-badge&logo=google-chrome&logoColor=white" /></a>` : '',
                email: () => state.email.trim() ? `<a href="mailto:${state.email.trim()}" target="_blank"><img src="https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white" /></a>` : '',
            };

            state.socialsOrder.forEach(key => {
                if (previewPresetSocials[key]) {
                    const badge = previewPresetSocials[key]();
                    if (badge) badges.push(badge);
                }
            });

            state.customSocials.forEach(s => {
                if ((s.name || '').trim() && (s.url || '').trim()) {
                    const cleanName = encodeURIComponent((s.name || '').trim().replace(/-/g, '--').replace(/_/g, '__'));
                    const logoSlug = encodeURIComponent((s.name || '').trim().toLowerCase().replace(/[^a-z0-9]/g, ''));
                    badges.push(`<a href="${s.url.trim()}" target="_blank"><img src="https://img.shields.io/badge/${cleanName}-${s.color || '3b82f6'}?style=for-the-badge&logo=${logoSlug}&logoColor=white" /></a>`);
                }
            });

            if (badges.length > 0) {
                sections.socials = `<h3>🌐 Connect with Me</h3><p style="display: flex; flex-wrap: wrap; gap: 8px;">${badges.join(' ')}</p>`;
            }

            // Skills
            const previewTechBadges = [];
            if (state.skills.length > 0) {
                previewTechBadges.push(`<img src="https://skillicons.dev/icons?i=${state.skills.join(',')}" alt="Skills" />`);
            }
            state.customTechBadges.forEach(b => {
                if ((b.name && b.name.trim()) || (b.imageUrl && b.imageUrl.trim())) {
                    previewTechBadges.push(getCustomTechBadgeImg(b));
                }
            });
            if (previewTechBadges.length > 0) {
                sections.tech = `<h3>🛠️ Tech Stack & Skills</h3><p style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">${previewTechBadges.join(' ')}</p>`;
            }

            // Featured Projects
            const validPreviewProjects = state.projects.filter(p => (p.title && p.title.trim()) || (p.description && p.description.trim()) || (p.thumbnail && p.thumbnail.trim()));
            if (validPreviewProjects.length > 0) {
                let projectsHtml = `<h3>💼 Featured Projects</h3><div class="project-preview-grid">`;
                validPreviewProjects.forEach(proj => {
                    const title = proj.title ? proj.title.trim() : 'Featured Project';
                    const desc = proj.description ? proj.description.trim() : '';
                    const thumb = proj.thumbnail ? proj.thumbnail.trim() : '';
                    const live = proj.liveUrl ? proj.liveUrl.trim() : '';
                    const repo = proj.repoUrl ? proj.repoUrl.trim() : '';
                    const tech = proj.techStack ? proj.techStack.trim() : '';
                    const primaryLink = live || repo || '#';

                    projectsHtml += `
                        <div class="project-preview-card">
                            ${thumb ? `<a href="${primaryLink}" target="_blank"><img src="${thumb}" alt="${title}" class="project-preview-card-thumb" onerror="this.style.display='none';" /></a>` : ''}
                            <div class="project-preview-card-body">
                                <h4 class="project-preview-card-title"><a href="${primaryLink}" target="_blank" style="color: inherit; text-decoration: none;">${title}</a></h4>
                                ${desc ? `<p class="project-preview-card-desc">${desc}</p>` : ''}
                                ${tech ? `<div class="project-preview-card-tags"><strong>Tech:</strong> ${tech}</div>` : ''}
                                <div class="project-preview-card-links">
                                    ${repo ? `<a href="${repo}" target="_blank">GitHub</a>` : ''}
                                    ${live ? `<a href="${live}" target="_blank">Live Demo</a>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                projectsHtml += `</div>`;
                sections.projects = projectsHtml;
            }

            // Support
            const support = [];
            if (state.buymeacoffee.trim()) support.push(`<a href="https://buymeacoffee.com/${state.buymeacoffee.trim()}" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="36" /></a>`);
            if (state.kofi.trim()) support.push(`<a href="https://ko-fi.com/${state.kofi.trim()}" target="_blank"><img src="https://ko-fi.com/img/githubbutton_sm.svg" height="36" /></a>`);
            if (state.patreon.trim()) support.push(`<a href="https://patreon.com/${state.patreon.trim()}" target="_blank"><img src="https://img.shields.io/badge/Patreon-F96854?style=for-the-badge&logo=patreon&logoColor=white" /></a>`);
            if (state.paypal.trim()) support.push(`<a href="https://paypal.me/${state.paypal.trim()}" target="_blank"><img src="https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" /></a>`);

            state.customSupport.forEach(sp => {
                if ((sp.name || '').trim() && (sp.url || '').trim()) {
                    const cleanName = encodeURIComponent((sp.name || '').trim().replace(/-/g, '--').replace(/_/g, '__'));
                    const logoSlug = encodeURIComponent((sp.name || '').trim().toLowerCase().replace(/[^a-z0-9]/g, ''));
                    support.push(`<a href="${sp.url.trim()}" target="_blank"><img src="https://img.shields.io/badge/${cleanName}-${sp.color || 'ea4aaa'}?style=for-the-badge&logo=${logoSlug}&logoColor=white" /></a>`);
                }
            });

            if (support.length > 0) {
                sections.support = `<h3>☕ Support Me</h3><p style="display: flex; flex-wrap: wrap; gap: 8px;">${support.join(' ')}</p>`;
            }

            // Snake Widget
            if (state.snake) {
                const userSnake = `https://raw.githubusercontent.com/${encodeURIComponent(user)}/${encodeURIComponent(user)}/output/github-contribution-grid-snake.svg`;
                const demoSnake = 'https://raw.githubusercontent.com/Platane/snk/output/github-contribution-grid-snake.svg';
                const snakeSrc = (state.hostMode === 'custom' && state.customHost)
                    ? `${state.customHost}/api/snake?username=${encodeURIComponent(user)}&theme=${encodeURIComponent(state.theme)}`
                    : userSnake;
                const demoNote = `<p id="snake-demo-note" style="display: none; margin: 6px auto 0; max-width: 520px; font-size: 12px; line-height: 1.45; color: var(--text-muted, #9ca3af); text-align: center;">⚠️ Showing the <strong>Platane/snk demo snake</strong> — this is sample animation only, <strong>not your real contribution graph</strong>. Run the Platane/snk GitHub Action on your profile repo to load your own snake.</p>`;
                sections.snake = `<h3>🐍 Contribution Graph Snake Animation</h3><p style="text-align: center;"><img src="${snakeSrc}" alt="Snake" style="max-width: 100%; height: auto;" onerror="this.onerror=null; this.src='${demoSnake}'; const n=document.getElementById('snake-demo-note'); if(n) n.style.display='block';" /></p>${demoNote}`;
            }

            // Trophies — own instance when Vercel hosting is selected (no third-party rate limits)
            if (state.trophies) {
                const trophyQuery = `?username=${encodeURIComponent(user)}&theme=radical&no-frame=false&no-bg=false&margin-w=4`;
                const trophyCdn = 'https://github-profile-trophy.screw-hand.vercel.app';
                const trophyCdnFallback = 'https://github-trophies.devomb.com';
                const isCustom = state.hostMode === 'custom' && state.customHost;
                if (isCustom) {
                    const selfTrophy = `${state.customHost}/api/trophies?username=${encodeURIComponent(user)}&theme=${encodeURIComponent(state.theme)}&column=6&margin-w=4&no-frame=false&no-bg=false`;
                    sections.trophies = `<h3>🏆 GitHub Trophies</h3><p style="text-align: center;"><img src="${selfTrophy}" alt="Trophies" style="max-width: 100%; height: auto;" onerror="this.onerror=null; this.style.display='none';" /></p>`;
                } else {
                    sections.trophies = `<h3>🏆 GitHub Trophies</h3><p style="text-align: center;"><img src="${trophyCdn}${trophyQuery}" alt="Trophies" style="max-width: 100%; height: auto;" onerror="this.onerror=null; this.src='${trophyCdnFallback}${trophyQuery}';" /></p>`;
                }
            }

            // Quotes
            if (state.quotes) {
                const quoteTheme = encodeURIComponent(state.theme || 'light');
                sections.quotes = `<h3>💬 Random Dev Quote</h3><p style="text-align: center;"><img src="https://quotes-github-readme.vercel.app/api?type=horizontal&theme=${quoteTheme}" alt="Quote" /></p>`;
            }

            // Analytics Widgets
            const hasWidgets = state.widgets.stats || state.widgets.languages || state.widgets.streak;
            if (hasWidgets) {
                let analyticsHtml = `<h2>📊 GitHub Analytics</h2>`;
                if (state.widgets.stats) {
                    analyticsHtml += `<p style="text-align: center;"><img src="${getWidgetUrl('stats')}" alt="Stats" style="max-width: 100%; height: auto; display: block; margin: 0 auto 12px;" onerror="this.src='https://github-stats-extended.vercel.app/api?username=torvalds&show_icons=true&theme=${encodeURIComponent(state.theme || 'light')}&locale=en'; this.onerror=null;" /></p>`;
                }
                if (state.widgets.languages) {
                    analyticsHtml += `<p style="text-align: center;"><img src="${getWidgetUrl('languages')}" alt="Languages" style="max-width: 100%; height: auto; display: block; margin: 0 auto 12px;" onerror="this.src='https://github-stats-extended.vercel.app/api/top-langs/?username=torvalds&layout=compact&theme=${encodeURIComponent(state.theme || 'light')}'; this.onerror=null;" /></p>`;
                }
                if (state.widgets.streak) {
                    analyticsHtml += `<p style="text-align: center;"><img src="${getWidgetUrl('streak')}" alt="Streak" style="max-width: 100%; height: auto;" /></p>`;
                }
                sections.analytics = analyticsHtml;
            }

            normalizeSectionsOrder();
            let html = headerHtml;
            const activeKeys = new Set();
            state.sectionsOrder.forEach(key => {
                if (sections[key]) {
                    activeKeys.add(key);
                    html += sections[key];
                }
            });
            renderedSectionKeys = activeKeys;

            previewEl.innerHTML = html;
        }

        function updateUI() {
            // Reflect the selected theme across the whole visual preview (project cards, profile, banner frame, borders)
            applyPreviewTheme(state.theme);

            // Update Full README markdown display
            const readmeEl = document.getElementById('full-readme-code');
            if (readmeEl) {
                readmeEl.textContent = generateFullReadme();
            }

            // Render visual profile preview
            renderGithubPreview();

            // Refresh socials and section reorder strips
            renderSocialsReorderStrip();
            renderSectionsReorderStrip();

            // Auto-persist workspace state to localStorage
            saveStateToStorage();
        }

        // Host Mode Switcher & API Tester
        function setHostMode(mode) {
            state.hostMode = mode;
            const btnCustom = document.getElementById('btn-mode-custom');
            const btnCommunity = document.getElementById('btn-mode-community');
            const customContainer = document.getElementById('custom-host-container');
            const communityNotice = document.getElementById('community-host-notice');

            if (mode === 'custom') {
                if (btnCustom) btnCustom.classList.add('active');
                if (btnCommunity) btnCommunity.classList.remove('active');
                if (customContainer) customContainer.style.display = 'block';
                if (communityNotice) communityNotice.style.display = 'none';

                const input = document.getElementById('config-host');
                if (input && !input.value && state.customHost) {
                    input.value = state.customHost;
                }
                const customVal = input ? input.value.trim().replace(/\/$/, '') : '';
                state.customHost = customVal;
            } else {
                if (btnCustom) btnCustom.classList.remove('active');
                if (btnCommunity) btnCommunity.classList.add('active');
                if (customContainer) customContainer.style.display = 'none';
                if (communityNotice) communityNotice.style.display = 'block';
            }
            updateUI();
        }

        async function testCustomHost() {
            const statusEl = document.getElementById('host-test-status');
            const btn = document.getElementById('test-host-btn');
            let hostVal = document.getElementById('config-host').value.trim().replace(/\/$/, '');

            if (!hostVal) {
                statusEl.className = 'test-status-text fail';
                statusEl.innerHTML = '⚠️ Please enter your deployed instance URL first.';
                return;
            }

            if (!hostVal.startsWith('http://') && !hostVal.startsWith('https://')) {
                hostVal = 'https://' + hostVal;
                document.getElementById('config-host').value = hostVal;
            }

            statusEl.className = 'test-status-text testing';
            statusEl.innerHTML = '⏳ Testing connection to ' + hostVal + '...';
            btn.disabled = true;

            const startTime = performance.now();
            try {
                const testUrl = `${hostVal}/api/stats?username=${encodeURIComponent(state.username || 'kent')}&theme=light`;
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 8000);

                await fetch(testUrl, { signal: controller.signal, mode: 'no-cors' });
                clearTimeout(timeoutId);

                const duration = Math.round(performance.now() - startTime);
                statusEl.className = 'test-status-text ok';
                statusEl.innerHTML = `✅ Connected successfully (${duration}ms)! Your deployment is ready for GitHub.`;

                state.customHost = hostVal;
                localStorage.setItem('gh_readme_custom_host', hostVal);
                updateUI();
            } catch (err) {
                statusEl.className = 'test-status-text fail';
                statusEl.innerHTML = `❌ Connection test failed (${err.name === 'AbortError' ? 'Timed out' : 'Unable to reach URL'}). Make sure your Vercel app is deployed.`;
            } finally {
                btn.disabled = false;
            }
        }

        // Copy & Download helpers
        function copyText(text, btn, successMsg = 'Copied! ✓') {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.textContent;
                btn.textContent = successMsg;
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.classList.remove('copied');
                }, 2000);
            });
        }

        function copyFullReadme(btn) {
            const code = generateFullReadme();
            copyText(code, btn, 'README Copied! ✓');
        }

        function downloadReadme() {
            const markdown = generateFullReadme();
            const blob = new Blob([markdown], { type: 'text/markdown;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'README.md';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Header "more actions" dropdown + step-nav scroll controls
        function toggleHeaderMenu(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('header-action-dropdown');
            const btn = document.getElementById('more-actions-btn');
            if (!menu) return;
            const open = menu.classList.toggle('open');
            if (btn) btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        document.addEventListener('click', (e) => {
            const menu = document.getElementById('header-action-dropdown');
            const btn = document.getElementById('more-actions-btn');
            if (!menu && !btn) return;
            if (btn && btn.contains(e.target)) return;
            if (menu && menu.classList.contains('open')) {
                menu.classList.remove('open');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const menu = document.getElementById('header-action-dropdown');
                const btn = document.getElementById('more-actions-btn');
                if (menu && menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        function scrollStepNav(direction) {
            const bar = document.getElementById('step-nav-bar');
            if (!bar) return;
            bar.scrollLeft += direction > 0 ? 260 : -260;
        }

        // Bind input listeners
        const inputBindings = [
            ['config-username', 'username', true],
            ['config-display-name', 'displayName'],
            ['config-bio', 'bio'],
            ['config-banner-text', 'bannerText'],
            ['config-banner-desc', 'bannerDesc'],
            ['config-banner-custom', 'bannerCustomUrl'],
            ['config-typing-lines', 'typingLines'],
            ['config-working-on', 'workingOn'],
            ['config-learning', 'learning'],
            ['config-collaborate-on', 'collaborateOn'],
            ['config-help-with', 'helpWith'],
            ['config-ask-me', 'askMe'],
            ['config-reach-me', 'reachMe'],
            ['config-projects-url', 'projectsUrl'],
            ['config-articles-url', 'articlesUrl'],
            ['config-resume-url', 'resumeUrl'],
            ['config-fun-fact', 'funFact'],
            ['config-linkedin', 'linkedin'],
            ['config-twitter', 'twitter'],
            ['config-youtube', 'youtube'],
            ['config-discord', 'discord'],
            ['config-medium', 'medium'],
            ['config-devto', 'devto'],
            ['config-hashnode', 'hashnode'],
            ['config-stackoverflow', 'stackoverflow'],
            ['config-leetcode', 'leetcode'],
            ['config-instagram', 'instagram'],
            ['config-twitch', 'twitch'],
            ['config-kaggle', 'kaggle'],
            ['config-website', 'website'],
            ['config-email', 'email'],
            ['config-buymeacoffee', 'buymeacoffee'],
            ['config-kofi', 'kofi'],
            ['config-patreon', 'patreon'],
            ['config-paypal', 'paypal'],
        ];

        inputBindings.forEach(([id, prop, isTrimmed]) => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', (e) => {
                    state[prop] = isTrimmed ? (e.target.value.trim() || 'your-username') : e.target.value;
                    if (['bannerText', 'bannerDesc', 'bannerCustomUrl', 'displayName', 'username'].includes(prop)) {
                        updateBannerThumbnail();
                    }
                    if (prop === 'username') {
                        const headerText = document.getElementById('header-username-text');
                        if (headerText) headerText.textContent = state.username;
                    }
                    updateUI();
                });

                if (prop === 'username') {
                    el.addEventListener('change', (e) => {
                        const newU = e.target.value.trim();
                        if (newU && newU !== 'your-username' && state.detectedForUser !== newU) {
                            detectTopLanguages(newU, false);
                        }
                        if (newU && newU !== 'your-username' && state.profileFetchedForUser !== newU) {
                            fetchGitHubProfileSmart(newU, false);
                        }
                    });
                }
            }
        });

        const hostInput = document.getElementById('config-host');
        if (hostInput) {
            hostInput.addEventListener('input', (e) => {
                const val = e.target.value.trim().replace(/\/$/, '');
                state.customHost = val;
                if (val) {
                    localStorage.setItem('gh_readme_custom_host', val);
                } else {
                    localStorage.removeItem('gh_readme_custom_host');
                }
                updateUI();
            });
        }

        // Workspace Persistence Helpers
        function saveStateToStorage() {
            try {
                if (!state.username || state.username === 'your-username') {
                    return;
                }
                const stateToSave = {
                    username: state.username,
                    displayName: state.displayName,
                    bio: state.bio,
                    bannerMode: state.bannerMode,
                    bannerPreset: state.bannerPreset,
                    bannerCustomUrl: state.bannerCustomUrl,
                    bannerText: state.bannerText,
                    bannerDesc: state.bannerDesc,
                    bannerAnimation: state.bannerAnimation,
                    bannerHeight: state.bannerHeight,
                    typingLines: state.typingLines,
                    workingOn: state.workingOn,
                    learning: state.learning,
                    collaborateOn: state.collaborateOn,
                    helpWith: state.helpWith,
                    askMe: state.askMe,
                    reachMe: state.reachMe,
                    projectsUrl: state.projectsUrl,
                    articlesUrl: state.articlesUrl,
                    resumeUrl: state.resumeUrl,
                    funFact: state.funFact,
                    linkedin: state.linkedin,
                    twitter: state.twitter,
                    youtube: state.youtube,
                    discord: state.discord,
                    medium: state.medium,
                    devto: state.devto,
                    hashnode: state.hashnode,
                    stackoverflow: state.stackoverflow,
                    leetcode: state.leetcode,
                    instagram: state.instagram,
                    twitch: state.twitch,
                    kaggle: state.kaggle,
                    website: state.website,
                    email: state.email,
                    buymeacoffee: state.buymeacoffee,
                    kofi: state.kofi,
                    patreon: state.patreon,
                    paypal: state.paypal,
                    snake: state.snake,
                    visitorCount: state.visitorCount,
                    trophies: state.trophies,
                    quotes: state.quotes,
                    skills: state.skills,
                    skillsDetected: state.skillsDetected,
                    detectedForUser: state.detectedForUser || '',
                    profileFetchedForUser: state.profileFetchedForUser || '',
                    detectedLanguages: state.detectedLanguages,
                    hostMode: state.hostMode,
                    customHost: state.customHost,
                    theme: state.theme,
                    widgets: state.widgets,
                    sectionsOrder: state.sectionsOrder,
                    socialsOrder: state.socialsOrder,
                    customBio: state.customBio,
                    customTechBadges: state.customTechBadges,
                    customSocials: state.customSocials,
                    customSupport: state.customSupport,
                    projects: state.projects,
                    currentStep: state.currentStep || 1,
                    workspaceLaunched: true
                };
                localStorage.setItem('gh_readme_builder_state', JSON.stringify(stateToSave));
            } catch (err) {
                // Ignore storage errors
            }
        }

        function loadStateFromStorage() {
            let saved;
            try {
                const raw = localStorage.getItem('gh_readme_builder_state');
                if (!raw) return false;
                saved = JSON.parse(raw);
                if (!saved || !saved.username || !saved.workspaceLaunched) return false;

                Object.keys(saved).forEach(key => {
                    if (key === 'widgets' && saved.widgets) {
                        state.widgets = { ...state.widgets, ...saved.widgets };
                        return;
                    }
                    if (key in state && saved[key] !== undefined) {
                        state[key] = saved[key];
                    }
                });
                normalizeSectionsOrder();
            } catch (err) {
                console.warn('Could not read saved state from localStorage:', err);
                return false;
            }

            // Hydrating the UI must never stop the workspace from being restored,
            // otherwise a single bad saved value would strand the user back on onboarding.
            try {
                hydrateWorkspaceUiFromState();
            } catch (err) {
                console.warn('Could not fully hydrate saved workspace UI:', err);
            }

            return true;
        }

        function hydrateWorkspaceUiFromState() {
            // Populate regular inputs
            inputBindings.forEach(([id, prop]) => {
                const el = document.getElementById(id);
                if (el && state[prop] !== undefined) {
                    el.value = state[prop];
                }
            });

            // Host URL input
            const hInput = document.getElementById('config-host');
            if (hInput && state.customHost) {
                hInput.value = state.customHost;
            }

            // Banner controls
            setBannerMode(state.bannerMode || 'preset');
            selectBannerPreset(state.bannerPreset || 'waving-gradient');

            // Addons checkboxes
            ['snake', 'visitorCount', 'trophies', 'quotes'].forEach(addonKey => {
                const chk = document.getElementById(addonKey === 'visitorCount' ? 'addon-visitor-count' : `addon-${addonKey}`);
                if (chk) {
                    chk.checked = !!state[addonKey];
                }
                const card = document.getElementById(`card-addon-${addonKey === 'visitorCount' ? 'visitor' : addonKey}`);
                if (card) {
                    card.classList.toggle('active', !!state[addonKey]);
                }
            });

            // Widget chips
            if (state.widgets) {
                Object.keys(state.widgets).forEach(wKey => {
                    const chip = document.querySelector(`.toggle-chip[data-widget-key="${wKey}"]`);
                    if (chip) chip.classList.toggle('active', !!state.widgets[wKey]);
                });
            }

            normalizeSectionsOrder();
            renderSectionsReorderStrip();

            // Theme chips
            if (state.theme) {
                document.querySelectorAll('.theme-chip').forEach(chip => {
                    chip.classList.toggle('active', chip.dataset.theme === state.theme);
                });
            }

            // Host mode
            setHostMode(state.hostMode || 'community');

            // Render dynamic repeaters
            renderCustomBioList();
            renderCustomTechBadgesList();
            renderCustomSocialsList();
            renderCustomSupportList();
            renderProjectsList();

            // Switch step
            if (state.currentStep && state.currentStep >= 1) {
                switchStep(state.currentStep);
            }
        }

        function resetWorkspacePrompt() {
            document.getElementById('reset-modal').style.display = 'flex';
        }

        function closeResetModal() {
            document.getElementById('reset-modal').style.display = 'none';
        }

        function confirmReset() {
            localStorage.removeItem('gh_readme_builder_state');
            localStorage.removeItem('gh_readme_custom_host');
            window.location.href = '/builder';
        }

        // Check for saved state on page load
        const hasRestored = loadStateFromStorage();

        // Initialize host mode on page load if not restored
        if (!hasRestored) {
            if (savedCustomHost) {
                if (hostInput) hostInput.value = savedCustomHost;
                setHostMode('custom');
            } else {
                setHostMode('community');
            }
        }

        // Initialize skills, projects and UI. Never let one failure block the
        // workspace from being usable.
        try {
            updateSkillsUI();
            applySkillsFilter();
            renderProjectsList();
            updateBannerThumbnail();
            updateUI();
        } catch (err) {
            console.warn('Builder UI initialization error:', err);
        }

        // Restore the workspace immediately when a saved session exists so a reload
        // keeps the user in the builder instead of falling back to onboarding.
        if (hasRestored && state.username && state.username !== 'your-username') {
            try {
                unlockWorkspace(state.username);
            } catch (err) {
                console.warn('Could not unlock restored workspace:', err);
            }
        }

        // On window load check URL query or restore workspace
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const queryUser = urlParams.get('username');

            if (queryUser && queryUser.trim()) {
                document.getElementById('onboarding-username-input').value = queryUser.trim();
                submitOnboarding();
            } else if (!hasRestored && state.username && state.username !== 'your-username') {
                unlockWorkspace(state.username);
            }
        });
    </script>
</body>
</html>



