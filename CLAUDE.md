# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

UxbertBlog is a Laravel 12 (PHP 8.2+) blog application with Vue 3 + Vite for frontend assets. It's a fairly standard Laravel monolith: Blade views render pages, with Vue components mountable for interactive pieces. Auth scaffolding is hand-rolled (laravel/ui was removed from route auto-registration; auth routes are declared explicitly in `routes/web.php`).

## Common commands

### PHP / Laravel
- Install deps: `composer install`
- Run all tests: `vendor/bin/phpunit` (or `php artisan test`)
- Run a single test file: `vendor/bin/phpunit tests/Feature/ManagePostsTest.php`
- Run a single test method: `vendor/bin/phpunit --filter a_user_can_create_a_post`
- Run migrations: `php artisan migrate`
- Local dev server: `php artisan serve`

Tests run against an in-memory SQLite DB (configured in `phpunit.xml`), so no local DB setup is needed to run the suite.

### JS / frontend
- Install deps: `npm install`
- Dev server (HMR): `npm run dev`
- Production build: `npm run build`

Vite is configured via `vite.config.js` with the Laravel Vite plugin, Vue 3 plugin, and legacy browser support. Entry points are `resources/sass/app.scss` and `resources/js/app.js`.

### Code style
- PHP style follows the `laravel` preset via StyleCI (`.styleci.yml`), with `no_unused_imports` disabled.

## Architecture

This is a small, conventional Laravel app centered around a single `Post` resource with ownership-based authorization.

- **Models** (`app/Models`): `Post` belongs to `User` via `owner_id` (not the default `user_id`); `User` has many `Post`s. `Post` uses `SoftDeletes`.
- **Authorization**: `App\Policies\PostPolicy::update` checks `$user->is($post->owner)`. Controllers call `$this->authorize('update', $post)` explicitly (policy isn't auto-discovered/registered elsewhere beyond the standard `AuthServiceProvider`).
- **Validation**: Form requests live in `app/Http/Requests` (e.g. `PostsRequest`) rather than inline controller validation.
- **Routing** (`routes/web.php`): Post CRUD routes are split — `create`, `edit`, `store`, `update` are behind the `auth` middleware group; `index` and `show` are public. Auth routes (login/register/password reset/email verification) are declared manually here rather than via `Auth::routes()`.
- **Views**: Blade templates in `resources/views`, organized by feature (`posts/`, `auth/`), with partials prefixed with `_` (e.g. `posts/_fields.blade.php`, `posts/_posts-list.blade.php`).
- **Path helper convention**: Models expose a `path()` method (e.g. `Post::path()` returns `/posts/{id}`) used both in controller redirects and tests, instead of building URLs with `route()` helpers inline.

## Testing conventions

- Feature tests (`tests/Feature`) hit routes and assert HTTP behavior/DB state; unit tests (`tests/Unit`) test model behavior directly. Both typically use `RefreshDatabase` and `WithFaker`.
- `Tests\TestCase::signIn($user = null)` is the standard helper for authenticating in tests — creates a user via factory if none is given, and calls `actingAs`.
- Test methods use the `/** @test */` docblock annotation with snake_case method names rather than a `test` prefix.
