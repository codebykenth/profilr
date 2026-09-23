@if (!empty($banner))
<p align="center">
  <img src="{{ $banner }}" alt="{{ $displayName }} Banner" />
</p>

@endif
# Hi there, I'm {{ $displayName }} 👋

@if (!empty($tagline))
> {{ $tagline }}

@endif
@if (!empty($typingLines))
<p align="center">
  <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&pause=1000&color=22C55E&center=true&vCenter=true&width=435&lines={{ urlencode($typingLines) }}" alt="Typing SVG" />
</p>

@endif
@if ($visitorCount)
<p align="left">
  <img src="https://komarev.com/ghpvc/?username={{ $username }}&label=Profile%20Views&color=0e75b6&style=flat" alt="{{ $username }} Profile Views" />
</p>

@endif
@if (!empty($workingOn) || !empty($learning) || !empty($collaborateOn) || !empty($helpWith) || !empty($askMe) || !empty($reachMe) || !empty($projectsUrl) || !empty($articlesUrl) || !empty($resumeUrl) || !empty($funFact))
### 🚀 About Me
@if (!empty($workingOn))
- 🔭 I'm currently working on **{{ $workingOn }}**
@endif
@if (!empty($learning))
- 🌱 I'm currently learning **{{ $learning }}**
@endif
@if (!empty($collaborateOn))
- 👯 I'm looking to collaborate on **{{ $collaborateOn }}**
@endif
@if (!empty($helpWith))
- 🤝 I'm looking for help with **{{ $helpWith }}**
@endif
@if (!empty($askMe))
- 💬 Ask me about **{{ $askMe }}**
@endif
@if (!empty($reachMe))
- 📫 How to reach me: **{{ $reachMe }}**
@endif
@if (!empty($projectsUrl))
- 👨‍💻 All of my projects are available at [{{ $projectsUrl }}]({{ $projectsUrl }})
@endif
@if (!empty($articlesUrl))
- 📝 I regularly write articles on [{{ $articlesUrl }}]({{ $articlesUrl }})
@endif
@if (!empty($resumeUrl))
- 📄 Know about my experiences [Read My Resume]({{ $resumeUrl }})
@endif
@if (!empty($funFact))
- ⚡ Fun fact: **{{ $funFact }}**
@endif

@endif
@if (!empty($linkedin) || !empty($twitter) || !empty($youtube) || !empty($discord) || !empty($medium) || !empty($devto) || !empty($hashnode) || !empty($stackoverflow) || !empty($leetcode) || !empty($website) || !empty($email))
### 🌐 Connect with Me
<p align="left">
@if (!empty($linkedin))
  <a href="{{ $linkedin }}" target="_blank"><img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn" /></a>
@endif
@if (!empty($twitter))
  <a href="{{ $twitter }}" target="_blank"><img src="https://img.shields.io/badge/X-000000?style=for-the-badge&logo=x&logoColor=white" alt="X" /></a>
@endif
@if (!empty($youtube))
  <a href="{{ $youtube }}" target="_blank"><img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" alt="YouTube" /></a>
@endif
@if (!empty($discord))
  <a href="{{ $discord }}" target="_blank"><img src="https://img.shields.io/badge/Discord-7289DA?style=for-the-badge&logo=discord&logoColor=white" alt="Discord" /></a>
@endif
@if (!empty($medium))
  <a href="{{ $medium }}" target="_blank"><img src="https://img.shields.io/badge/Medium-12100E?style=for-the-badge&logo=medium&logoColor=white" alt="Medium" /></a>
@endif
@if (!empty($devto))
  <a href="{{ $devto }}" target="_blank"><img src="https://img.shields.io/badge/DEV.to-0A0A0A?style=for-the-badge&logo=devdotto&logoColor=white" alt="DEV.to" /></a>
@endif
@if (!empty($hashnode))
  <a href="{{ $hashnode }}" target="_blank"><img src="https://img.shields.io/badge/Hashnode-2962FF?style=for-the-badge&logo=hashnode&logoColor=white" alt="Hashnode" /></a>
@endif
@if (!empty($stackoverflow))
  <a href="{{ $stackoverflow }}" target="_blank"><img src="https://img.shields.io/badge/Stack_Overflow-FE7A16?style=for-the-badge&logo=stack-overflow&logoColor=white" alt="Stack Overflow" /></a>
@endif
@if (!empty($leetcode))
  <a href="{{ $leetcode }}" target="_blank"><img src="https://img.shields.io/badge/LeetCode-FFA116?style=for-the-badge&logo=leetcode&logoColor=black" alt="LeetCode" /></a>
@endif
@if (!empty($website))
  <a href="{{ $website }}" target="_blank"><img src="https://img.shields.io/badge/Website-4B32C3?style=for-the-badge&logo=google-chrome&logoColor=white" alt="Website" /></a>
@endif
@if (!empty($email))
  <a href="mailto:{{ $email }}" target="_blank"><img src="https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white" alt="Email" /></a>
@endif
</p>

@endif
@if (!empty($skills))
### 🛠️ Tech Stack & Skills
<p align="left">
  <img src="https://skillicons.dev/icons?i={{ $skillsStr }}" alt="Tech Stack" />
</p>

@endif
@if (!empty($projects))
### 💼 Featured Projects

<table>
@foreach (array_chunk($projects, 2) as $row)
  <tr>
@foreach ($row as $proj)
    <td width="50%" valign="top">
      <h4 align="center"><a href="{{ $proj['liveUrl'] ?? $proj['repoUrl'] ?? '#' }}"><b>{{ $proj['title'] ?? 'Featured Project' }}</b></a></h4>
@if (!empty($proj['thumbnail']))
      <a href="{{ $proj['liveUrl'] ?? $proj['repoUrl'] ?? '#' }}">
        <img src="{{ $proj['thumbnail'] }}" alt="{{ $proj['title'] ?? 'Project' }}" width="100%" />
      </a>
@endif
@if (!empty($proj['description']))
      <p>{{ $proj['description'] }}</p>
@endif
@if (!empty($proj['techStack']))
      <p><strong>Tech Stack:</strong> {{ $proj['techStack'] }}</p>
@endif
@php
  $pLinks = [];
  if (!empty($proj['repoUrl'])) $pLinks[] = '<a href="' . e($proj['repoUrl']) . '"><b>📂 GitHub</b></a>';
  if (!empty($proj['liveUrl'])) $pLinks[] = '<a href="' . e($proj['liveUrl']) . '"><b>🚀 Live Demo</b></a>';
@endphp
@if (!empty($pLinks))
      <p align="center">{!! implode(' &bull; ', $pLinks) !!}</p>
@endif
    </td>
@endforeach
@if (count($row) === 1)
    <td width="50%" valign="top"></td>
@endif
  </tr>
@endforeach
</table>

@endif
@if (!empty($buymeacoffee) || !empty($kofi) || !empty($patreon) || !empty($paypal))
### ☕ Support Me
<p align="left">
@if (!empty($buymeacoffee))
  <a href="https://buymeacoffee.com/{{ $buymeacoffee }}" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="36" alt="Buy Me A Coffee" /></a>
@endif
@if (!empty($kofi))
  <a href="https://ko-fi.com/{{ $kofi }}" target="_blank"><img src="https://ko-fi.com/img/githubbutton_sm.svg" height="36" alt="Support on Ko-fi" /></a>
@endif
@if (!empty($patreon))
  <a href="https://patreon.com/{{ $patreon }}" target="_blank"><img src="https://img.shields.io/badge/Patreon-F96854?style=for-the-badge&logo=patreon&logoColor=white" alt="Patreon" /></a>
@endif
@if (!empty($paypal))
  <a href="https://paypal.me/{{ $paypal }}" target="_blank"><img src="https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="PayPal" /></a>
@endif
</p>

@endif
@if ($trophies)
### 🏆 GitHub Trophies

@if (!empty($customHost))
<p align="center">
  <img src="{{ $customHost }}/api/trophies?username={{ $username }}&theme={{ $theme }}" alt="GitHub Trophies" />
</p>
@else
<p align="center">
  <img src="https://github-profile-trophy.screw-hand.vercel.app/?username={{ $username }}&theme=radical&no-frame=false&no-bg=false&margin-w=4" alt="GitHub Trophies" />
</p>
@endif

@endif
@if ($quotes)
### 💬 Random Dev Quote
<p align="center">
  <img src="https://quotes-github-readme.vercel.app/api?type=horizontal&theme=radical" alt="Dev Quote" />
</p>

@endif
@if ($showSnake)
### 🐍 Contribution Graph Snake Animation

@if (!empty($customHost))
<p align="center">
  <img src="{{ $customHost }}/api/snake?username={{ $username }}&theme={{ $theme }}" alt="GitHub Contribution Snake" />
</p>
@else
<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/{{ $username }}/{{ $username }}/output/github-contribution-grid-snake-dark.svg">
  <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/{{ $username }}/{{ $username }}/output/github-contribution-grid-snake.svg">
  <img alt="GitHub Contribution Snake" src="https://raw.githubusercontent.com/{{ $username }}/{{ $username }}/output/github-contribution-grid-snake.svg" />
</picture>
@endif

@endif
@if ($showProfile || $showStats || $showLanguages || $showStreak || $showPinned)
## 📊 GitHub Analytics

@if (!empty($customHost))
@if ($showProfile)
<p align="center">
  <img src="{{ $customHost }}/api/profile?username={{ $username }}&theme={{ $theme }}" alt="Profile Card" />
</p>
@endif

@if ($showStats || $showLanguages)
<p align="center">
@if ($showStats)
  <img src="{{ $customHost }}/api/stats?username={{ $username }}&theme={{ $theme }}" alt="GitHub Stats" />
@endif
@if ($showLanguages)
  <img src="{{ $customHost }}/api/languages?username={{ $username }}&theme={{ $theme }}" alt="Top Languages" />
@endif
</p>
@endif

@if ($showStreak)
<p align="center">
  <img src="{{ $customHost }}/api/streak?username={{ $username }}&theme={{ $theme }}" alt="GitHub Streak" />
</p>
@endif

@if ($showPinned)
<p align="center">
  <img src="{{ $customHost }}/api/pinned?username={{ $username }}&theme={{ $theme }}" alt="Pinned Repos" />
</p>
@endif
@else
@if ($showStats || $showLanguages)
<p align="center">
@if ($showStats)
  <img src="https://github-stats-extended.vercel.app/api?username={{ $username }}&show_icons=true&theme={{ $theme }}&locale=en" alt="GitHub Stats" />
@endif
@if ($showLanguages)
  <img src="https://github-stats-extended.vercel.app/api/top-langs/?username={{ $username }}&layout=compact&theme={{ $theme }}" alt="Top Languages" />
@endif
</p>
@endif

@if ($showStreak)
<p align="center">
  <img src="https://streak-stats.demolab.com?user={{ $username }}&theme={{ $theme }}" alt="GitHub Streak" />
</p>
@endif
@endif

@endif
---

<p align="center">
  <i>Generated with <a href="{{ $repoUrl }}">GitHub Profile README Generator</a></i>
</p>
