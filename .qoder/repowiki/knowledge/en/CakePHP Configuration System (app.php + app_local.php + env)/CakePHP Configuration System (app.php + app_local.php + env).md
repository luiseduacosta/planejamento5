---
kind: configuration_system
name: CakePHP Configuration System (app.php + app_local.php + env)
category: configuration_system
scope:
    - '**'
source_files:
    - config/app.php
    - config/app_local.php
    - config/bootstrap.php
    - config/paths.php
    - config/.env.example
    - src/Application.php
    - src/Middleware/HostHeaderMiddleware.php
---

This CakePHP 5 skeleton uses the framework's built-in Configure system with a two-file override pattern and optional .env support.

Loading order and layering:
1. config/paths.php defines filesystem constants (ROOT, CONFIG, WWW_ROOT, TMP, LOGS, CACHE, RESOURCES, CORE_PATH, CAKE).
2. config/bootstrap.php registers PhpConfig, loads config/app.php via Configure::load('app', 'default', false), then conditionally overlays config/app_local.php if it exists.
3. src/Application::bootstrap() calls parent::bootstrap(), which in turn invokes config/bootstrap.php.
4. After loading, bootstrap consumes top-level keys into subsystems: Cache, Datasources, EmailTransport, Mailer, Log, Security.salt via Configure::consume(...).

Two-file configuration convention:
- config/app.php — shared defaults for all environments (debug toggles, cache engines, email transports, Datasource driver defaults, logging, DebugKit, TestSuite). Values are read from environment variables through env('KEY', default) so they can be overridden without changing source files.
- config/app_local.php — per-environment overrides (local DB credentials, debug flag, security salt). This file is intentionally gitignored; production deployments supply equivalent values via environment variables or their own app_local.php.

Environment variable integration:
- config/.env.example documents every supported variable (APP_*, SECURITY_SALT, DATABASE_URL, DATABASE_TEST_URL, EMAIL_TRANSPORT_DEFAULT_URL, CACHE_*_URL, LOG_*_URL, DEBUG_KIT_*).
- The .env loader using josegonzalez\Dotenv\Loader is present but commented out in config/bootstrap.php; when enabled it parses config/.env into $_ENV / $_SERVER before app.php reads them via env().

Runtime application of config:
- config/bootstrap.php applies timezone, mbstring encoding, intl locale, error/exception traps, mobile/tablet request detectors, and sets Router::fullBaseUrl() based on App.fullBaseUrl (with development fallback to HTTP_HOST).
- src/Middleware/HostHeaderMiddleware enforces that App.fullBaseUrl is set in production and rejects requests whose Host header does not match, preventing Host Header Injection attacks.

Key configuration sections:
- App — namespace, encoding, locales, base/webroot paths, asset URLs, resource path overrides.
- Security.salt — encryption key, sourced from SECURITY_SALT.
- Cache — FileEngine backends for _cake_translations_ and _cake_model_, durations shortened in debug mode.
- Datasources — MySQL defaults plus a test connection; both accept a url DSN via DATABASE_URL / DATABASE_TEST_URL.
- EmailTransport / Email — transport profiles overridable via EMAIL_TRANSPORT_DEFAULT_URL.
- Log — file-based debug, error, and queries scopes, each accepting a url DSN.
- Session — defaults to PHP sessions; supports cache/database handlers.
- DebugKit / TestSuite — developer-only toggles.

Rules developers should follow:
- Never commit secrets: keep config/app_local.php out of VCS and supply values via environment variables or deployment-time generation.
- Prefer env('VAR', default) inside app.php/app_local.php rather than hardcoding per-environment values.
- In production always set APP_FULL_BASE_URL (or App.fullBaseUrl) so HostHeaderMiddleware can validate incoming hosts.
- Use DATABASE_URL / DATABASE_TEST_URL DSN strings instead of individual host/user/password keys when possible.
- Keep config/app.php as immutable defaults; put environment-specific overrides only in app_local.php or environment variables.