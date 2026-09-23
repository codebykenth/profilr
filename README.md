<div align="center">

# 🎨 GitHub README Generator

**Dynamic SVG widgets for your GitHub profile README**

Stats • Languages • Streak • Profile • Pinned Repos

[![Import to Vercel](https://vercel.com/button)](https://vercel.com/new)

</div>

---

> [!TIP]
> **Why Deploy Your Own?**
> GitHub README widgets receive traffic from anyone who visits your profile. By deploying your own free instance on Vercel with your personal token, you get:
> - **Dedicated Rate Limits**: A full 5,000 requests/hour GitHub API quota exclusively for your widgets.
> - **Private Repo Stats**: Access to private repository counts and statistics.
> - **100% Personal Uptime**: Never affected by shared server load.

## ⚡ Quick Start (3 Steps)

### 1. Fork this repository
Click the **Fork** button at the top right of this repository to create your own copy.

### 2. Deploy to Vercel
1. Go to [vercel.com/new](https://vercel.com/new)
2. Import your forked repository
3. Add the following environment variables:

| Variable | Value | Required | Description |
|----------|-------|----------|-------------|
| `GITHUB_TOKEN` | Your [GitHub Personal Access Token](https://github.com/settings/tokens) | ✅ | Authenticates GraphQL requests for stats |
| `ENABLE_API` | `true` | ✅ | Enables `/api/*` endpoints on your deployment |

> **Token scopes needed:** `read:user` (public data) and optionally `repo` (for private repo stats)

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
