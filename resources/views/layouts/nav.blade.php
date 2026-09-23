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
