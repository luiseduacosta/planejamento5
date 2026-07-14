---
kind: dependency_management
name: Composer-based PHP dependency management with automated updates
category: dependency_management
scope:
    - '**'
source_files:
    - composer.json
    - composer.lock
    - .github/dependabot.yml
---

This CakePHP 5 skeleton project uses Composer as its sole dependency manager for PHP packages. Dependencies are declared in composer.json and pinned to exact versions via the committed composer.lock file, ensuring reproducible builds across environments.

### System overview
- Package manager: Composer (PHP)
- Registry: Packagist (default public registry); no private repository or repositories override is configured
- Lockfile: composer.lock is committed to version control, locking every transitive dependency to a specific commit hash
- Plugin ecosystem: Uses cakephp/plugin-installer (^2.0) so CakePHP plugins are auto-discovered under plugins/; dealerdirect/phpcodesniffer-composer-installer is allowed but not required
- Autoloading: PSR-4 namespaces App\ -> src/, App\Test\ -> tests/, plus Cake\Test\ mapped into vendor for framework tests

### Key files
- composer.json — declares runtime (require) and development (require-dev) dependencies, autoload rules, plugin allow-list, scripts, and platform constraints
- composer.lock — committed lockfile containing exact resolved versions and source references for all packages
- .github/dependabot.yml — GitHub Dependabot configuration that opens weekly PRs for both composer and github-actions ecosystems

### Architecture and conventions
- Version ranges: Runtime deps use caret ranges (e.g., ^3.0, ^5.0, 5.3.*) allowing compatible minor/patch upgrades; dev deps follow the same pattern
- Platform pinning: php >=8.2 enforced via require.php and config.platform-check = true so Composer refuses installs on unsupported runtimes
- Plugin policy: Only cakephp/plugin-installer and dealerdirect/phpcodesniffer-composer-installer are explicitly whitelisted under config.allow-plugins; all others must be approved at install time
- Script hooks: post-install-cmd and post-create-project-cmd invoke App\Console\Installer::postInstall for one-time setup tasks after Composer runs
- Dev tooling: Code quality and testing tools (bake, debug_kit, phpunit, phpcs) live exclusively in require-dev so production installs stay lean

### Rules developers should follow
1. Never edit composer.lock by hand — add/remove packages through composer require / composer remove so the lockfile stays consistent.
2. Keep composer.lock committed — it is the single source of truth for reproducible deployments; do not ignore it.
3. Use caret ranges (^x.y) for new dependencies rather than fixed pins, letting Composer resolve safe upgrades while the lockfile captures the exact build.
4. Add new plugins to config.allow-plugins before installing them, otherwise Composer will prompt interactively and break CI.
5. Update via Dependabot — rely on the weekly scheduled PRs for routine bumps; review and merge them to keep the app current without manual intervention.
6. Separate dev vs. runtime — put test/lint/bake tooling in require-dev only so production composer install --no-dev remains minimal.
7. Respect the PHP platform constraint — any new dependency must be compatible with php >=8.2; Composer will fail fast if not.