<div align="center">

# 🎨 Profilr - GitHub README Generator

**Dynamic SVG widgets for your GitHub profile README**

Stats • Languages • Streak • Profile • Pinned Repos

[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2Fcodebykenth%2Fprofilr&project-name=profilr&repository-name=profilr&env=GITHUB_TOKEN&envDescription=Enter%20your%20GitHub%20Personal%20Access%20Token%20(requires%20read%3Auser%2Crepo%20scopes)%20to%20enable%20stats%20fetching.&envLink=https%3A%2F%2Fgithub.com%2Fsettings%2Ftokens)

</div>

---

### About Profilr

Profilr is an open-source tool that generates dynamic, customizable SVG widgets for your GitHub profile README. Built with Laravel and designed for free, one-click serverless deployment on Vercel, it connects directly to the GitHub GraphQL API to display real-time contribution stats, streaks, top languages, and pinned repositories, all with dedicated rate limits and support for private repo data.

---

> [!TIP]
> **Why Deploy Your Own?**
> GitHub README widgets receive traffic from anyone who visits your profile. By deploying your own free instance on Vercel with your personal token, you get:
> - **Dedicated Rate Limits**: A full 5,000 requests/hour GitHub API quota exclusively for your widgets.
> - **Private Repo Stats**: Access to private repository counts and statistics.
> - **100% Personal Uptime**: Never affected by shared server load.

## ⚡ Quick Start (3 Steps)

> **Pick ONE path — don't mix them.** Going to [vercel.com/new](https://vercel.com/new) and typing a repo name that doesn't exist on GitHub yet will fail. Either let Vercel clone for you (Option A), or fork first and then import (Option B).

### Option A — Deploy button (recommended, creates the repo for you)
1. Click **Deploy with Vercel** above.
2. Vercel shows **Create Git Repository** — pick a name and choose **Private** or **Public**, then **Create**. This clones the template into your GitHub account automatically. (This is how you get a private copy — you don't pre-create it.)
3. When prompted for env vars, fill in the only one:

| Variable | Required | How to get it |
|----------|----------|---------------|
| `GITHUB_TOKEN` | ✅ | Your [GitHub Personal Access Token](https://github.com/settings/tokens) — scopes `read:user` (public data) + `repo` (private repo stats) |

That's the only input. Every other environment variable is set automatically when the app boots on Vercel (`bootstrap/app.php`): the `/tmp` cache paths, `array` cache/session drivers, `stderr` logging, `ENABLE_API=true`, and `APP_KEY` (derived per deployment as a stable SHA-256 of your token — the app is stateless, so there's nothing sensitive to encrypt; override it in the dashboard if you want hardening). Forks are **API-only** by design (`ENABLE_UI` stays off) — the landing page stays on the main instance. Nothing else needs configuring.

### Option B — Fork first, then import
1. Click **Fork** (top right) to copy the repo to your account (choose Private there if you want).
2. Go to [vercel.com/new](https://vercel.com/new) → **Import** your fork (it must already exist on GitHub).
3. Add `GITHUB_TOKEN` + `APP_KEY` (same as above) and deploy.

### 3. Use in your README
Once deployed, embed widgets in your GitHub profile README using `<img>` tags:

```md
![Stats](https://your-app.vercel.app/api/stats?theme=dark)
```

That's it! 🚀

---

## 📊 Available Widgets

### Stats Card
Shows total stars, commits, PRs, issues, and contributions.
```md
<img src="https://your-app.vercel.app/api/stats?theme=dark" alt="GitHub Stats" />
```

### Top Languages
Bar chart of most-used programming languages.
```md
<img src="https://your-app.vercel.app/api/languages?theme=dark&limit=8" alt="Top Languages" />
```

### Streak Stats
Current and longest contribution streak.
```md
<img src="https://your-app.vercel.app/api/streak?theme=dark" alt="Streak Stats" />
```

### Profile Card
Avatar, bio, follower/following counts, and join date.
```md
<img src="https://your-app.vercel.app/api/profile?theme=dark" alt="Profile Card" />
```

### Pinned Repos
Showcase your pinned repositories with stars and forks.
```md
<img src="https://your-app.vercel.app/api/pinned?theme=dark" alt="Pinned Repos" />
```

### Full README Template
Get a complete README.md with all widgets embedded:
```
GET https://your-app.vercel.app/api/readme?theme=dark
```

---

## 🎨 Themes

All widgets support a `?theme=` parameter:

| Theme | | Theme | |
|-------|---|-------|---|
| `light` | Default light | `dark` | GitHub dark |
| `radical` | Neon pink | `dracula` | Dracula purple |
| `tokyonight` | Tokyo Night blue | `gruvbox` | Gruvbox warm |
| `nord` | Nord cool | `catppuccin` | Catppuccin pastel |
| `onedark` | One Dark | `solarized` | Solarized |
| `rose_pine` | Rosé Pine | `ayu` | Ayu dark |

---

## 🔧 API Reference

| Endpoint | Description | Extra Params |
|----------|-------------|--------------|
| `GET /api/stats` | Stars, commits, PRs, issues | `username`, `theme` |
| `GET /api/languages` | Most used languages | `username`, `theme`, `limit` (max 12) |
| `GET /api/streak` | Contribution streaks | `username`, `theme` |
| `GET /api/profile` | Avatar, bio, followers | `username`, `theme` |
| `GET /api/pinned` | Pinned repositories | `username`, `theme` |
| `GET /api/readme` | Full README template | `username`, `theme` |

> **Note:** If `username` is omitted, the app uses the authenticated user (from `GITHUB_TOKEN`).

---

## 🏗️ Tech Stack

- **Backend:** Laravel 13 (PHP 8.4)
- **Templates:** Blade SVG rendering
- **Deployment:** Vercel (serverless PHP via `vercel-php@0.9.0`)
- **API:** GitHub GraphQL API v4
- **Database:** None — fully stateless

---

## 🖥️ Local Development

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/profilr.git
cd profilr

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Add your GitHub token to .env
# GITHUB_TOKEN=ghp_your_token_here

# Start the server
php artisan serve
```

Visit `http://localhost:8000` to see the landing page.

---

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.
