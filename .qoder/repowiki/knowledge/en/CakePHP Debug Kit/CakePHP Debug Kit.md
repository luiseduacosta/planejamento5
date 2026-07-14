---
kind: external_dependency
name: CakePHP Debug Kit
slug: debug-kit
category: external_dependency
category_hints:
    - framework_behavior
scope:
    - '**'
---

### DebugKit Development Tool
- Development-only debugging tool that stores request data in SQLite database at `tmp/debug_kit.sqlite`.
- Requires write permissions to the SQLite file in the tmp directory — common issue when file has read-only permissions (644).
- Used for debugging SQL queries, request/response data, and application performance during development.
- Should not be used in production environments.