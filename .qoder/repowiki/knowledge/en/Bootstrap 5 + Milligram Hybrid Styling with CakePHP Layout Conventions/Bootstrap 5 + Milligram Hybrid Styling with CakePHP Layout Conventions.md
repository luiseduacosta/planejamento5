---
kind: frontend_style
name: Bootstrap 5 + Milligram Hybrid Styling with CakePHP Layout Conventions
category: frontend_style
scope:
    - '**'
source_files:
    - templates/layout/default.php
    - webroot/css/cake.css
    - webroot/css/milligram.min.css
    - src/View/Helper/FormHelper.php
    - webroot/css/home.css
---

This CakePHP 5 application uses a hybrid frontend styling approach combining Bootstrap 5 and the Milligram CSS framework, layered over CakePHP's default skeleton styles.

**Core Frameworks & Libraries:**
- **Bootstrap 5.3.3** — loaded via CDN in `templates/layout/default.php` for the primary UI framework (navbar, grid, utilities, components)
- **Milligram v1.4.1** — included as `webroot/css/milligram.min.css`, providing minimal base styles that are overridden by custom CSS
- **CakePHP Skeleton Styles** — `webroot/css/cake.css` defines design tokens and overrides Milligram defaults with CakePHP branding colors

**Design Token System:**
The application centralizes visual tokens in `webroot/css/cake.css` using CSS custom properties (`:root`):
- Brand colors: `--color-cakephp-red`, `--color-cakephp-blue`, `--color-cakephp-gray`
- Message system colors: success/warning/error/info variants with background, text, and border tokens
- Typography tokens: `--color-links`, `--color-headings`, `--color-main-bg`
- Font families: Raleway for headings/navigation, Helvetica Neue for body text

**Layout Architecture:**
- Single layout file `templates/layout/default.php` provides consistent page structure with Bootstrap navbar, container-based content area, and footer
- Flash messages rendered through `$this->Flash->render()` with styled message classes (.message.success/.error/.warning/.info)
- Responsive breakpoints handled via Bootstrap's built-in responsive utilities plus custom media queries in cake.css

**CSS Organization Pattern:**
- Global styles: `webroot/css/cake.css` (design tokens, component overrides)
- Base reset: `webroot/css/normalize.min.css`, `webroot/css/milligram.min.css`
- Page-specific styles: `webroot/css/home.css` for the home page only
- Fonts served from `webroot/font/` including Raleway web fonts and CakePHP dingbats icon font

**Form Styling Convention:**
Custom FormHelper in `src/View/Helper/FormHelper.php` integrates Bootstrap form classes through the `bootstrapType()` method, ensuring consistent form styling across all generated forms.

**Key Files:**
- `templates/layout/default.php` — main layout with Bootstrap integration
- `webroot/css/cake.css` — design tokens and component overrides
- `webroot/css/milligram.min.css` — base CSS framework
- `src/View/Helper/FormHelper.php` — Bootstrap form class integration
- `webroot/css/home.css` — home page specific styles