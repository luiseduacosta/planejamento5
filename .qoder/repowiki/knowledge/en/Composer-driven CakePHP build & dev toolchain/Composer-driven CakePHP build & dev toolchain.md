---
kind: build_system
name: Composer-driven CakePHP build & dev toolchain
category: build_system
scope:
    - '**'
source_files:
    - composer.json
    - src/Console/Installer.php
    - phpunit.xml.dist
    - phpstan.neon
    - psalm.xml
    - phpcs.xml
    - .github/workflows/ci.yml
---

This repository is a CakePHP 5.x skeleton application whose build and development pipeline is centered on Composer, with GitHub Actions providing CI. There are no Makefiles, Dockerfiles, or custom shell build scripts — the project relies on standard PHP tooling and Composer hooks to bootstrap, lint, type-check, and test the codebase.

**Dependency management and bootstrapping**
- `composer.json` declares `cakephp/cakephp ^5.3`, authentication/authorization plugins, migrations, bake, debug kit, PHPUnit, and CodeSniffer as dependencies. The package type is `project` (not a library), so it is installed directly rather than consumed as a dependency.
- PSR-4 autoloading maps `App\` → `src/` for production and `App\Test\` → `tests/` for tests.
- Composer post-install hooks (`post-install-cmd`, `post-create-project-cmd`) invoke `App\Console\Installer::postInstall`, which:
  - Copies `config/app_local.example.php` → `config/app_local.php` if missing
  - Creates writable runtime directories (`logs`, `tmp`, `tmp/cache/*`, `tmp/sessions`, `tmp/tests`) listed in `Installer::WRITABLE_DIRS`
  - Optionally sets world-writable permissions on `tmp/` and `logs/` when run interactively
  - Generates a random `Security.salt` and writes it into `config/app_local.php` by replacing the `__SALT__` placeholder
  - Delegates to Codeception's installer if that plugin is present
- `config/.env.example` exists alongside `app_local.*.php`; the `.env` file itself is not autoloaded by default in this skeleton.

**Static analysis and coding standards**
- **PHPStan**: `phpstan.neon` runs at level 8 against `src/`, bootstraps via `config/bootstrap.php`, and treats PHPDoc types as uncertain (`treatPhpDocTypesAsCertain: false`).
- **Psalm**: `psalm.xml` targets `src/` at error level 2 and ignores `vendor/`.
- **PHP_CodeSniffer**: `phpcs.xml` enforces the `CakePHP` ruleset plus an exclusion for native return-type hints in controllers; both `src/` and `tests/` are scanned. Scripts `@cs-check` and `@cs-fix` wrap `phpcs` / `phpcbf`.

**Testing**
- `phpunit.xml.dist` defines an `app` suite scanning `tests/TestCase/`, boots via `tests/bootstrap.php`, registers the CakePHP fixture extension, excludes `src/Console/Installer.php` from coverage, and sets unlimited memory.
- The `@test` Composer script invokes `phpunit --colors=always`.
- CI passes `DATABASE_TEST_URL=sqlite://./testdb.sqlite` so unit tests can run without a real database.

**CI pipeline (GitHub Actions)**
- `.github/workflows/ci.yml` runs two jobs on push to `5.x`/`5.next`/`6.x` and on all pull requests:
  - `testsuite`: matrix of PHP 8.2 + lowest deps and PHP 8.5 + highest deps, installs via `ramsey/composer-install`, runs post-install hook, then `vendor/bin/phpunit`.
  - `coding-standard`: PHP 8.2 with `cs2pr` and `phpstan:1.12` tools pre-installed; runs `phpcs --report=checkstyle | cs2pr` and `phpstan`. A dummy `SECURITY_SALT` env var is set for static analysis.
- `.github/workflows/stale.yml` handles stale issue/pr triage (unrelated to building).

**Conventions developers should follow**
- Keep runtime secrets out of version control; use `config/app_local.php` (copied from `app_local.example.php`) and environment variables instead of editing `config/app.php`.
- New writable paths must be added to `Installer::WRITABLE_DIRS` so they are created and permissioned during install.
- Add new Composer scripts under the `scripts` key and reference them via `@script-name` (e.g., `@check` runs `@test` then `@cs-check`).
- Place static-analysis configuration changes in the existing `phpstan.neon`, `psalm.xml`, and `phpcs.xml` files rather than passing CLI flags manually.
- When adding new PHPUnit suites, extend the `<testsuites>` block in `phpunit.xml.dist` and register any needed extensions under `<extensions>`.