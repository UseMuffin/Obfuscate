# Obfuscate

[![Build Status](https://img.shields.io/travis/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://travis-ci.org/UseMuffin/Obfuscate)
[![Coverage](https://img.shields.io/coveralls/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://coveralls.io/r/UseMuffin/Obfuscate)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/obfuscate.svg?style=flat-square)](https://packagist.org/packages/muffin/obfuscate)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

CakePHP 3 support for ID obfuscation.

## Install

Using [Composer][composer]:

```
composer require muffin/obfuscate:dev-master
```

You then need to load the plugin. You can use the shell command:

```
bin/cake plugin load Muffin/Obfuscate
```

or by manually adding statement shown below to `bootstrap.php`:

```php
Plugin::load('Muffin/Obfuscate');
```

## Usage

First, you will need to choose one of the two (2) built-in strategies:

- `OptimusStrategy` which requires the [] package, or
- `TinyStrategy` which requires the [] package.

Once you have installed the required package, you are ready to set up obfuscation.

In any table, add the behavior like so (example showing the `TinyStrategy`):

```php
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

// ...

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    'strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')
]);
```

By default, the behavior will listen to the `Model.afterSave` and `Model.beforeFind` events.

It will also make available on the table two (2) methods:

- `obfuscate(string $str)` 
- `elucidate(string $str)`

## Patches & Features

* Fork
* Mod, fix
* Test - this is important, so it's not unintentionally broken
* Commit - do not mess with license, todo, version, etc. (if you do change any, bump them into commits of
their own that I can ignore when I pull)
* Pull request - bonus point for topic branches

To ensure your PRs are considered for upstream, you MUST follow the [CakePHP coding standards][standards].

## Bugs & Feedback

http://github.com/usemuffin/obfuscate/issues

## License

Copyright (c) 2015, [Use Muffin][muffin] and licensed under [The MIT License][mit].

[cakephp]:http://cakephp.org
[composer]:http://getcomposer.org
[mit]:http://www.opensource.org/licenses/mit-license.php
[muffin]:http://usemuffin.com
[standards]:http://book.cakephp.org/3.0/en/contributing/cakephp-coding-conventions.html
