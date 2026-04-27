# AI Agent Instructions for OTAPlatform

## Purpose
This file helps AI coding agents get productive quickly in the OTAPlatform Laravel repository.

## Project type
- Laravel 11 PHP application with Blade/Vite frontend.
- Docker-ready local development stack.
- Mixed admin and B2C travel booking platform.

## Key commands
- `composer install`
- `npm install`
- `npm run dev`
- `npm run build`
- `php artisan test`
- `./vendor/bin/phpunit`
- `docker compose up -d`
- `docker compose down`

## Important files and directories
- `routes/web.php` — main app routing, including admin and B2C pages.
- `routes/api.php` — Sanctum-protected API endpoints.
- `app/Http/Controllers` — controller logic; B2C controllers live under `App\Http\Controllers\B2c`.
- `app/Services` — business-domain services such as booking, flight search, pricing, and rules.
- `app/Models` — Eloquent models.
- `app/Http/Middleware` — custom auth and request middleware.
- `resources/views` — Blade templates.
- `resources/js` and `resources/sass` — Vite frontend entry points.
- `app/helpers.php` — global helper functions autoloaded by Composer.
- `docker-compose.yml` — local Docker stack configuration.

## Architecture notes
- Keep controllers thin and prefer service classes in `app/Services` for domain logic.
- Use Laravel conventions for routing, middleware, and dependency injection.
- Frontend assets are compiled via Vite.
- The project includes payment integration, PDF generation, and datatable support.

## Environment
- PHP 8.2 required.
- MySQL backend configured via `docker-compose.yml`.
- Local app entry via Nginx container on port `8080`.
- The repository contains `.env.example`; copy or rename to `.env` for local development.

## Agent behavior guidance
- Prefer referencing Laravel docs for framework behavior, and the repo’s own `app/Services` and route definitions for business rules.
- Do not assume there is an existing AI agent customization file in this repo.
- When making changes, check `routes/web.php`, `app/Services`, and `app/Http/Controllers` first for related behavior.
- For UI changes, update Blade templates and Vite assets under `resources/views`, `resources/js`, and `resources/sass`.

## Notes
- No existing `.github/copilot-instructions.md` or `AGENTS.md` file was present before this addition.
