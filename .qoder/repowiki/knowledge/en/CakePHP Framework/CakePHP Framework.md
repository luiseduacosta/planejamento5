---
kind: external_dependency
name: CakePHP Framework
slug: cakephp
category: external_dependency
category_hints:
    - vendor_identity
scope:
    - '**'
---

### CakePHP 5.x Application
- This is a CakePHP 5.3 skeleton application using the standard MVC structure with Controllers, Models (Table/Entity), Views, and Migrations.
- Database configuration is managed through `config/app_local.php` which is git-ignored for security.
- Uses CakePHP's built-in authentication and authorization plugins (`cakephp/authentication`, `cakephp/authorization`).
- Development server runs on port 8765 via `bin/cake server -p 8765`.
- Follows CakePHP conventions: controllers in `src/Controller/`, models in `src/Model/Table/` and `src/Model/Entity/`, views in `templates/`.