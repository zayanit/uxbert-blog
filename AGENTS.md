# Repository Guidelines

## Project Structure

This is a Laravel application. Application code is under `app/` (controllers,
models, policies, middleware, and providers). Browser routes live in
`routes/`; configuration is in `config/`; database migrations, factories, and
seeders are in `database/`. Blade templates and frontend assets are in
`resources/`, with compiled public assets in `public/`. PHPUnit tests are split
between `tests/Unit/` and `tests/Feature/`.

## Build, Test, and Development Commands

- `composer install` installs PHP dependencies and runs Laravel package discovery.
- `php artisan migrate` applies database migrations; use `php artisan serve` to
  run the local application.
- `vendor/bin/phpunit` runs the Unit and Feature suites. The suite uses an
  in-memory SQLite database through `phpunit.xml`.
- `npm install` installs frontend dependencies; `npm run dev` starts Vite for
  development and `npm run build` creates production assets.
- `composer validate --strict`, `composer check-platform-reqs`, and
  `composer audit` mirror the dependency checks in GitHub Actions.

## Coding Style and Naming

Follow Laravel conventions and PSR-12-compatible PHP formatting: four-space
indentation, typed declarations where practical, StudlyCase classes, camelCase
methods and variables, and descriptive snake_case database fields. Name tests
after the behavior they verify, such as `ManagePostsTest` or
`a_user_can_update_a_post`.

## Testing Guidelines

Add unit tests for isolated models and policies, and feature tests for routes,
middleware, authentication, and database behavior. Run the full suite with
`vendor/bin/phpunit` before submitting changes; new behavior should include
regression coverage where practical.

## Commits and Pull Requests

Use concise Conventional Commit-style prefixes found in history, for example
`fix:`, `test:`, `ci:`, or `chore(deps-dev):`. Keep commits focused. Pull
requests should explain the behavior or risk addressed, list validation
commands and results, and call out configuration or migration requirements.

## Security and Configuration

Never commit `.env`, credentials, API keys, or generated artifacts. Start from
`.env.example`, generate an application key with `php artisan key:generate`,
and review `SECURITY.md` before changing authentication or deployment behavior.
