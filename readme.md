# laravel-debugbar-phpstorm

Plugin **laravel-debugbar** that provide **button** to open **vscode**

This package code is based on https://github.com/barryvdh/laravel-debugbar and https://github.com/erlangp/laravel-debugbar-vscode

## How to use

Move mouse pointer to text that contains file path, then the button will appear. Click to open the path in phpstorm :D

![Screenshot 1](screenshots/laravel-debugbar-vscode.screnshot-1.png)
![Screenshot 2](screenshots/laravel-debugbar-vscode.screnshot-2.png)

## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require  raveren/laravel-debugbar-phpstorm  --dev
```

### Laravel 5.5+:

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
ErlangParasu\DebugbarVscode\ServiceProvider::class,
```

### Lumen:

For Lumen, register a different Provider in `bootstrap/app.php`:

```php
if (env('APP_DEBUG')) {
    $app->register(ErlangParasu\DebugbarVscode\LumenServiceProvider::class);
}
```
