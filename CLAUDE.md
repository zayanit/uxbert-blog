# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

UxbertBlog is a Laravel 12 (PHP 8.2+) blog application with Vue 3 + Vite for frontend assets. It's a fairly standard Laravel monolith: Blade views render pages, with Vue components mountable for interactive pieces. Auth scaffolding is hand-rolled (laravel/ui was removed from route auto-registration; auth routes are declared explicitly in `routes/web.php`).

Although it runs on Laravel 12, the app keeps the **pre-Laravel-11 skeleton**: middleware is registered in `app/Http/Kernel.php`, routes are loaded by `app/Providers/RouteServiceProvider.php`, and `bootstrap/app.php` binds the HTTP/Console kernels the old way. Put new middleware, providers, and route files there, not in a `bootstrap/app.php` `withMiddleware()`/`withRouting()` chain.

`AGENTS.md` has the shared repo guidelines. Commits use Conventional Commit prefixes (`fix:`, `test:`, `ci:`, `chore(deps-dev):`).

## Common commands

### PHP / Laravel
- Install deps: `composer install`
- Run all tests: `vendor/bin/phpunit` (or `php artisan test`)
- Run a single test file: `vendor/bin/phpunit tests/Feature/ManagePostsTest.php`
- Run a single test method: `vendor/bin/phpunit --filter a_user_can_create_a_post`
- Run migrations: `php artisan migrate`
- Local dev server: `php artisan serve`

Tests run against an in-memory SQLite DB (configured in `phpunit.xml`), so no local DB setup is needed to run the suite.

CI (`.github/workflows/laravel.yml`, PHP 8.2) also runs these checks, which you can run locally before pushing dependency changes: `composer validate --strict`, `composer check-platform-reqs`, `composer audit`.

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
- **Authorization**: `App\Policies\PostPolicy::update` checks `$user->is($post->owner)`. The policy is mapped explicitly in `AuthServiceProvider::$policies`, and controllers call `$this->authorize('update', $post)` explicitly. `PostsRequest::authorize()` returns `true`, so form requests do no authorization.
- **Mass assignment**: `PostsController` passes `$request->all()` to `create`/`update`, so `Post::$fillable` (which includes `owner_id`) is the only thing that controls which fields users can write.
- **Validation**: Form requests live in `app/Http/Requests` (e.g. `PostsRequest`) rather than inline controller validation.
- **Routing** (`routes/web.php`): Post CRUD routes are split — `create`, `edit`, `store`, `update` are behind the `auth` middleware group; `index` and `show` are public. `posts/create` must stay registered before `posts/{post}`, or the wildcard catches it. There's no destroy route yet, even though `Post` is soft-deletable. `/home` (`HomeController`, auth enforced in its constructor) lists the signed-in user's own posts. Auth routes (login/register/password reset/email verification) are declared manually here rather than via `Auth::routes()`.
- **Views**: Blade templates in `resources/views`, organized by feature (`posts/`, `auth/`), with partials prefixed with `_` (e.g. `posts/_fields.blade.php`, `posts/_posts-list.blade.php`).
- **Path helper convention**: Models expose a `path()` method (e.g. `Post::path()` returns `/posts/{id}`) used both in controller redirects and tests, instead of building URLs with `route()` helpers inline.

## Testing conventions

- Feature tests (`tests/Feature`) hit routes and assert HTTP behavior/DB state; unit tests (`tests/Unit`) test model behavior directly. Both typically use `RefreshDatabase` and `WithFaker`.
- `Tests\TestCase::signIn($user = null)` is the standard helper for authenticating in tests — creates a user via factory if none is given, and calls `actingAs`.
- Test methods use the `/** @test */` docblock annotation with snake_case method names rather than a `test` prefix.
