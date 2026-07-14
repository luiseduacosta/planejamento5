---
kind: error_handling
name: CakePHP Exception Pipeline and Error Templates
category: error_handling
scope:
    - '**'
source_files:
    - config/bootstrap.php
    - src/Controller/ErrorController.php
    - templates/Error/error400.php
    - templates/Error/error500.php
    - templates/layout/error.php
    - src/Middleware/HostHeaderMiddleware.php
    - src/Controller/PagesController.php
---

This CakePHP 5 skeleton uses the framework's built-in exception pipeline rather than a custom error system. The approach is configuration-driven, template-based, and relies on PSR-15 middleware exceptions.

**System overview**
- Global handlers are registered in `config/bootstrap.php` via `ErrorTrap` (for PHP errors/warnings/notices) and `ExceptionTrap` (for thrown exceptions), both configured from `Configure::read('Error')`.
- The `src/Controller/ErrorController.php` extends `AppController` but deliberately omits `parent::initialize()` to avoid running application logic during error rendering; it forces the view path to `templates/Error/`.
- HTTP-level errors are represented by CakePHP exception classes (`NotFoundException`, `ForbiddenException`, `BadRequestException`, `InternalErrorException`) thrown from controllers and middleware.
- Rendering is delegated to dedicated templates under `templates/Error/` (`error400.php`, `error500.php`) with a shared layout at `templates/layout/error.php`. In debug mode the templates switch to the `dev_error` layout and expose stack traces via `Cake\Error\Debugger`.

**Key files**
- `config/bootstrap.php` — registers `ErrorTrap` and `ExceptionTrap`
- `src/Controller/ErrorController.php` — error-rendering controller
- `templates/Error/error400.php` — 4xx page template
- `templates/Error/error500.php` — 5xx page template
- `templates/layout/error.php` — common error layout
- `src/Middleware/HostHeaderMiddleware.php` — example of throwing `BadRequestException` / `InternalErrorException`
- `src/Controller/PagesController.php` — example of throwing `ForbiddenException` / `NotFoundException`

**Architecture and conventions**
- Application code throws CakePHP `*Exception` types; the framework maps them to HTTP status codes and routes them through `ErrorController` → `templates/Error/*`.
- Sensitive debugging information (file/line links, stack traces) is only shown when `Configure::read('debug')` is true; production renders a minimal message.
- The `ErrorController` intentionally does not call `parent::initialize()`, so authentication, logging, or other `AppController` hooks do not run during error responses.
- Host-header validation is enforced early in the request lifecycle via a PSR-15 middleware that throws `InternalErrorException` (missing config) or `BadRequestException` (mismatched host).

**Rules developers should follow**
- Throw CakePHP exception classes (`NotFoundException`, `ForbiddenException`, `BadRequestException`, `InternalErrorException`) instead of returning arbitrary HTTP codes.
- Do not add business logic to `ErrorController`; keep error rendering stateless.
- Keep `templates/Error/*` free of sensitive data; rely on the debug-flagged `dev_error` layout for stack traces.
- Use `Configure::read('Error')` to tune global error behavior (logging, reporting, renderer) rather than overriding handlers per-request.