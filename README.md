# laravel-debugbar-phpstorm

Plugin for [**Laravel Debugbar**](https://github.com/barryvdh/laravel-debugbar) that provides a button to jump to visible file in **PHPStorm**.

---

## Installation

```
composer require raveren/laravel-debugbar-phpstorm  --dev
```


This plugin reuses your [Ignition settings](https://flareapp.io/docs/ignition-for-laravel/configuration#remote-development-server-support) `IGNITION_LOCAL_SITES_PATH` and `IGNITION_REMOTE_SITES_PATH` in your `.env` file. E.g.

```apacheconf
IGNITION_LOCAL_SITES_PATH=<project root dir>
IGNITION_REMOTE_SITES_PATH=/home/vagrant/<project dir>
```

It might work without you setting those, it might not, currently I seem to be the only one using this, if this gains popularity I will improve the documentation..

## What it does?

![Screenshot 1](screenshots/laravel-debugbar-vscode.screnshot-1.png)
![Screenshot 2](screenshots/laravel-debugbar-vscode.screnshot-2.png)

Click on the pencil button and this file will open in PHPStorm (if you have it locally of course).

This package is a very quick and hacky fork from https://github.com/erlangp/laravel-debugbar-vscode with a few improvements.

License: MIT
