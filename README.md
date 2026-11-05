# Arc

![CI](https://github.com/rgusseinov/arc-php-framework/actions/workflows/ci.yml/badge.svg)

A lightweight PHP framework built from scratch for learning: DI container with autowiring, router, middleware pipeline, events, PSR-3 logging, console commands and migrations.

This repository is the **framework** (library). To start an application, use the skeleton:
[rgusseinov/arc-skeleton](https://github.com/rgusseinov/arc-skeleton).

## Requirements

- PHP 8.1+
- Composer
- MySQL (only for database features)

## Quick start

```bash
composer create-project rgusseinov/arc-skeleton my-app
cd my-app
cp .env.example .env     # fill in DB_HOST, DB_NAME, DB_USER, DB_PASS
php arc serve            # http://127.0.0.1:8000
```

## Console commands

```bash
php arc                  # list all commands
php arc serve            # dev server (--host=0.0.0.0 --port=8080)
php arc migrate          # run migrations
php arc make:migration   # generate a migration
php arc make:controller  # generate a controller
php arc make:model       # generate a model
```

Target directories and namespaces of generators are set in `config/generators.php` of your application.

## What is inside

| Component | Namespace |
|---|---|
| DI container (autowiring) | `Arc\Framework\Core` |
| Router, kernel, middleware | `Arc\Framework\Core`, `Arc\Framework\Middlewares` |
| Events and listeners | `Arc\Framework\Events` |
| PSR-3 logger | `Arc\Framework\Logging` |
| Database, query builder, migrations | `Arc\Framework\Database` |
| Console | `Arc\Framework\Console` |

## Extending

Create a service provider and list it in `config/app.php` of your application:

```php
use Arc\Framework\Core\Container;
use Arc\Framework\Providers\ServiceProviderInterface;

class MyServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton(MyService::class);
    }

    public function boot(Container $container): void
    {
    }
}
```

`register()` only registers bindings; use `boot()` to resolve services.

## Development

```bash
composer install
composer test        # PHPUnit
composer analyse     # PHPStan
composer cs          # code style check (PSR-12)
composer cs:fix      # fix code style
```

## License

MIT
