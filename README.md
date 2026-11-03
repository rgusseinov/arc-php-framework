# Arc

![CI](https://github.com/rgusseinov/arc-php-framework/actions/workflows/ci.yml/badge.svg)

A lightweight PHP framework built from scratch for learning: DI container with autowiring, router, middleware pipeline, events, PSR-3 logging, console commands and migrations.

## Requirements

- PHP 8.1+
- Composer
- MySQL (only for database features)

## Installation

```bash
git clone git@github.com:rgusseinov/arc-php-framework.git
cd arc-php-framework
composer install
cp .env.example .env
```

## Usage

```bash
php arc serve             # start the dev server (http://127.0.0.1:8000)
php arc migrate           # run migrations
php arc make:controller   # generate a controller
php arc make:migration    # generate a migration
php arc                   # list all commands
```

## Development

```bash
composer test       # PHPUnit
composer analyse    # PHPStan
composer cs         # code style check (PSR-12)
composer cs:fix     # fix code style
```

## License

MIT