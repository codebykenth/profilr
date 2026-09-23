Git Skills & Workflow StandardsThis document establishes standard Git conventions, branch management workflows, and commit standards for consistent, readable, and trackable project history.1. Branch Naming ConventionsAlways create branches using a categorized prefix followed by a concise, hyphenated (kebab-case) description.Standard Prefixesfeat/ — New feature or user-facing functionalityfix/ — Bug fix or hotfixdocs/ — Documentation additions or modificationsrefactor/ — Code restructuring with no functional or behavioral changeperf/ — Performance optimizationtest/ — Adding, refactoring, or updating test suiteschore/ — Tooling, dependency updates, and build configurationNaming Pattern<type>/<ticket-or-issue-id>-<short-description>
Examplesfeat/PROJ-101-user-authenticationfix/PROJ-204-cart-calculation-bugdocs/update-api-readmerefactor/database-client-pooling2. Commit Message Conventions (Conventional Commits)Commit messages must follow the Conventional Commits specification to facilitate automated changelogs and semantic versioning.Structure<type>(<optional scope>): <description>

[optional body]

[optional footer(s)]
Typesfeat: A new feature for the user or systemfix: A bug fix for the user or systemdocs: Changes to documentation onlystyle: Changes that do not alter code logic (formatting, spacing, semi-colons)refactor: Code change that neither fixes a bug nor adds a featureperf: Code change that improves execution speed or resource usagetest: Adding missing tests or correcting existing test coveragebuild: Changes affecting the build system or external dependencies (npm, bundlers)ci: Changes to CI/CD configuration files and scripts (GitHub Actions, etc.)chore: Maintenance tasks, housekeeping, repo-level config changesCommit RulesWrite the subject line in the imperative mood (e.g., "add auth guard", not "added" or "adds").Do not capitalize the first letter of the subject line.Do not place a period (.) at the end of the subject line.Keep the subject line under 72 characters.Use an exclamation point (!) before the colon to mark breaking changes (e.g., feat(api)!: drop legacy v1 endpoints).Examples# Simple feature
git commit -m "feat(auth): add google oauth provider"

# Bug fix with scope
git commit -m "fix(payment): prevent duplicate card charge submissions"

# Breaking change with detailed body
git commit -m "feat(api)!: migrate auth tokens from cookies to bearer header

BREAKING CHANGE: endpoints now require Authorization: Bearer <token>"
3. Creating & Managing BranchesAlways synchronize your base branch before branching off.# 1. Switch to the main branch
git switch main
# (or: git checkout main)

# 2. Pull latest upstream changes
git pull origin main

# 3. Create and switch to your new feature branch
git switch -c feat/PROJ-101-user-authentication
# (or: git checkout -b feat/PROJ-101-user-authentication)
4. Daily Development & StagingKeep commits atomic (focused on a single logical change) and commit frequently.# Inspect changed and untracked files
git status

# Inspect specific line-by-line changes
git diff

# Stage specific files
git add src/auth/service.ts tests/auth.test.ts

# Stage all tracked modified files
git add -u

# Commit with a conventional message
git commit -m "feat(auth): implement token verification logic"

# Push branch and configure upstream tracking
git push -u origin feat/PROJ-101-user-authentication
5. Integrating Changes (Merging & Rebasing)Option A: Pull Request / Merge Commit (Standard Team Flow)Preserves branch history and context.# Ensure local main is current
git switch main
git pull origin main

# Merge the feature branch into main
git merge --no-ff feat/PROJ-101-user-authentication

# Push merged main to remote
git push origin main
Option B: Squash & Merge (Linear History)Condenses all commits from the feature branch into one clean commit on main.git switch main
git pull origin main

git merge --squash feat/PROJ-101-user-authentication
git commit -m "feat(auth): complete PROJ-101 user authentication flow"
git push origin main
Option C: Rebase Workflow (Keep Branch Linear Before PR)Replays feature commits on top of latest main to resolve conflicts prior to review.# While on your feature branch
git fetch origin
git rebase origin/main

# If conflicts occur:
# 1. Resolve conflict markers in files
# 2. Stage resolved files:
git add <resolved-files>
# 3. Continue the rebase:
git rebase --continue

# Push updated rebased branch (use force-with-lease for safety)
git push --force-with-lease origin feat/PROJ-101-user-authentication
6. Branch CleanupDelete branches after successful integration to keep the repository tidy.# 1. Switch away from the feature branch
git switch main

# 2. Delete local branch (safe delete)
git branch -d feat/PROJ-101-user-authentication

# 3. Delete remote branch
git push origin --delete feat/PROJ-101-user-authentication

# 4. Prune local tracking branches deleted on the remote
git fetch --prune
7. Emergency Quick ReferenceTaskCommandDiscard unstaged file changesgit restore <file>Unstage a staged filegit restore --staged <file>Temporarily stash working changesgit stash -uRestore stashed changesgit stash popAmend the last commit messagegit commit --amend -m "new message"View concise graph loggit log --oneline --graph --decorate -n 15