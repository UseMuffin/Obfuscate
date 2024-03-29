# Obfuscate

[![Build Status](https://img.shields.io/github/actions/workflow/status/UseMuffin/Obfuscate/ci.yml?style=flat-square&branch=master
)](https://github.com/UseMuffin/Obfuscate/actions?query=workflow%3ACI+branch%3Amaster)
[![Coverage Status](https://img.shields.io/codecov/c/github/UseMuffin/Obfuscate.svg?style=flat-square)](https://codecov.io/github/UseMuffin/Obfuscate)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/obfuscate.svg?style=flat-square)](https://packagist.org/packages/muffin/obfuscate)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

Primary key obfuscation for CakePHP using HashIds, Optimus, Base62 and/or custom obfuscation strategies.

## Installation

Install the plugin using [Composer](https://getcomposer.org):

```
composer require muffin/obfuscate
```

Load the plugin by either running this console command:

```
bin/cake plugin load Muffin/Obfuscate
```

or by manually adding the following line to `src/Application.php`:

```php
$this->addPlugin('Muffin/Obfuscate');
```

Lastly, install the required obfuscation library depending on the strategy class
you want to use and stated below.

## Built-in obfuscation strategies

Use the [HashIdStrategy](http://hashids.org/) if you want to:

- obfuscate your primary keys with short, unique, non-sequential ids
- present record ids like 347 as strings like “yr8”

```
composer require hashids/hashids
```

Use the [OptimusStrategy](https://github.com/jenssegers/optimus) if you want to:

- obfuscate your primary keys with integers based on Knuth's integer hash
- present record ids like 347 as integers like 372555994

```
composer require jenssegers/optimus
```

Use the [Base62Strategy](https://github.com/tuupola/base62) if you want to:

- obfuscate your primary keys with base62 strings and integers
- present record ids like 347 as strings like "vk"

```
composer require tuupola/base62
```

You can also create your own strategy classes by implementing the `StrategyInterface`.

## Usage

### 1. Attaching the behavior

Prepare for obfuscation by attaching the Obfuscate behavior to your table(s)
and specifying which strategy you want to use as shown in the following examples.

```php
use Muffin\Obfuscate\Model\Behavior\Strategy\HashIdStrategy;

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    // Strategy constructor parameter:
    // $salt - Random alpha numeric string. You can also set "Obfuscate.salt"
    // $minLength (optional) - The minimum hash length. Default: 0
    // $alphabet (optional) - Custom alphabet to generate hash from. Default: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    // config instead of passing salt to construction.
    // DO NOT USE same salt as set for "Security.salt" config.
    'strategy' => new HashIdStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B', 10, 'abcdefghijklmnopqrstuvwxyz')
]);
```

```php
use Muffin\Obfuscate\Model\Behavior\Strategy\OptimusStrategy;

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    // Strategy constructor parameters:
    // $prime - Large prime number lower than 2147483647
    // $inverse - The inverse prime so that (PRIME * INVERSE) & MAXID == 1
    // $random - A large random integer lower than 2147483647
    // You can use vendor/bin/optimus spark to generate these set of numbers.
    'strategy' => new OptimusStrategy(2123809381, 1885413229, 146808189)
]);
```

```php
use Muffin\Obfuscate\Model\Behavior\Strategy\Base62Strategy;

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    // Strategy constructor parameters:
    // $set - Random alpha-numeric set where each character must only be used exactly once
    'strategy' => new Base62Strategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')
]);
```

> Please note that attaching the behavior is totally unobtrusive and will do
> absolutely nothing until you use one of the custom finders.

### 2. Using the custom finders

This plugin comes with the following two custom finders that are responsible for
the actual obfuscation (cloaking) and elucidation (uncloaking) process:

- `findObfuscated`: used to find records using an obfuscated (cloaked) primary key
- `findObfuscate`: used to obfuscate (cloak) all primary keys in a find result set

### findObfuscated

Use this finder if you want to look up a record using an obfuscated id.
The plugin will elucidate (uncloak) the obfuscated id and will execute the find
using the "normal" primary key as it is used inside your database.

CakePHP example:
```php
public function view($id)
{
    $article = $this->Articles->find('obfuscated')
        ->where(['id' => $id]) // For e.g. if value for $id is 'S' it will search for actual id 1
        ->first();
}
```

Crud plugin example:
```php
public function view($id)
{
    $this->Crud->on('beforeFind', function (EventInterface $event) {
        $event->subject()->query->find('obfuscated');
    });
}
```

#### findObfuscate

Use this finder if you want the plugin to obfuscate all "normal" primary keys
found in a find result set.

CakePHP example:
```php
public function index()
{
    $articles = $this->Articles->find('obfuscate');
}
```

Crud plugin example:
```php
public function index()
{
    $this->Crud->on('beforePaginate', function (EventInterface $event) {
        $event->subject()->query->find('obfuscate');
    });
}
```

### Methods

Attaching the behavior also makes the following two methods available on the table:

- `obfuscate(string $str)`
- `elucidate(string $str)`

## Pro tips

### Authentication

A fairly common use case is applying obfuscation to user ids. To ensure the
Authentication plugin properly handles obfuscated ids, specify the `obfuscated` finder
using the `finder` key in your [identifier's resolver](https://book.cakephp.org/authentication/3/en/identifiers.html) config.

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

Copyright (c) 2015-Present, [Use Muffin][muffin] and licensed under [The MIT License][mit].

[cakephp]:http://cakephp.org
[composer]:http://getcomposer.org
[mit]:http://www.opensource.org/licenses/mit-license.php
[muffin]:http://usemuffin.com
[standards]:http://book.cakephp.org/5.0/en/contributing/cakephp-coding-conventions.html
