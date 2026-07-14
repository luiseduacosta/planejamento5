---
kind: logging_system
name: CakePHP Log Framework with File Sinks
category: logging_system
scope:
    - '**'
source_files:
    - config/app.php
    - config/bootstrap.php
---

This CakePHP 5 skeleton uses the framework's built-in logging system based on PSR-3 (Psr\Log\LoggerInterface) via Cake\Log\Log. There is no custom logger implementation — all configuration and usage relies on the core framework.

Framework and initialization
- Logging is configured in config/app.php under the Log key and applied during bootstrap via Log::setConfig(Configure::consume('Log')) in config/bootstrap.php.
- The default sink engine is Cake\Log\Engine\FileLog, writing to files under the LOGS directory (typically logs/).

Predefined log channels
The skeleton defines three named channels, each backed by a separate file:
- debug -> logs/debug.log for levels notice, info, debug
- error -> logs/error.log for levels warning, error, critical, alert, emergency
- queries -> logs/queries.log scoped to cake.database.queries (enabled only when a datasource has 'log' => true)

Each channel supports an optional url override via environment variables (LOG_DEBUG_URL, LOG_ERROR_URL, LOG_QUERIES_URL) so sinks can be swapped at runtime without changing code.

Error integration
The Error config section (config/app.php) controls how exceptions are handled:
- log defaults to true so uncaught exceptions are written through the logging system.
- skipLog lets you exclude specific exception classes from being logged.
- trace and traceFormat control backtrace inclusion and serialization format.

Query logging
Database query logging is opt-in per connection: set 'log' => true on a Datasource entry (see Datasources.default.log / Datasources.test.log), then route those entries to the queries channel via its scopes filter (cake.database.queries).

Conventions for developers
- Use the PSR-3 facade Cake\Log\Log (e.g., Log::debug(), Log::error()) or inject Psr\Log\LoggerInterface in services/controllers.
- Choose the appropriate level: debug/info/notice for development/runtime diagnostics; warning/error/critical for actionable issues.
- Scope structured logs using the $context array rather than string interpolation so downstream consumers can index fields.
- For database queries, enable logging per connection instead of globally to avoid excessive output.
- Keep sensitive data out of log messages; use Debugger.outputMask if you need to redact values in debug dumps.