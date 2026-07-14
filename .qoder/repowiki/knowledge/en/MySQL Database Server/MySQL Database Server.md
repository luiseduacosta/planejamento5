---
kind: external_dependency
name: MySQL Database Server
slug: mysql
category: external_dependency
category_hints:
    - vendor_identity
scope:
    - '**'
---

### MySQL Database
- Primary database backend configured as `ess_apps` on localhost with root/root credentials.
- Test database uses separate `test_ess_apps` database or SQLite fallback via `DATABASE_TEST_URL` environment variable.
- Database migrations are managed through CakePHP Migrations plugin with schema files in `config/Migrations/`.
- Connection settings are environment-specific and loaded from `config/app_local.php`.