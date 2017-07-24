# Obfuscate

[![Build Status](https://img.shields.io/travis/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://travis-ci.org/UseMuffin/Obfuscate)
[![Coverage](https://img.shields.io/coveralls/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://coveralls.io/r/UseMuffin/Obfuscate)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/obfuscate.svg?style=flat-square)](https://packagist.org/packages/muffin/obfuscate)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

Primary key obfuscation for CakePHP 3 using HashIds, Optimus, Tiny and/or custom obfuscation strategies.

## Requirements

- CakePHP 3.0+

## Installation

Install the plugin using [Composer](https://getcomposer.org):

```
composer require muffin/obfuscate:1.0.x-dev
```

You then need to load the plugin by either running this shell command:

```
bin/cake plugin load Muffin/Obfuscate
```

or by manually adding the following line to ``config/bootstrap.php``:

```php
Plugin::load('Muffin/Obfuscate');
```

Lastly, composer install (any combination of) the obfuscation libraries you
want to use in your application:

```
composer install hashids/hashids
composer install jenssegers/optimus
composer install zackkitzmiller/tiny
```

## Built-in obfuscation strategies

Use the [HashIdStrategy](http://hashids.org/) if you want to:

- obfuscate your primary keys with short, unique, non-sequential ids
- present record ids like 347 as strings like “yr8”

Use the [OptimusStrategy](https://github.com/jenssegers/optimus) if you want to:

- obfuscate your primary keys with integers based on Knuth's integer hash
- present record ids like 347 as integers like 372555994

Use the [TinyStrategy](https://github.com/zackkitzmiller/tiny-php) if you want to:

- obfuscate your primary keys with base62 strings and integers
- present record ids like 347 as strings like "vk"

> You may also choose to create your own custom strategies, feel free to PR.

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
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    // Strategy constructor parameters:
    // $set - Random alpha-numeric set where each character must only be used exactly once
    'strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')
]);
```

> Please note that attaching the behavior is totally unobtrusive and will do
> absolutely nothing until you use one of the custom finders.

### 2. Using the custom finders

This plugin comes with the following two custom finders that are responsible for
the actual obfuscation (cloaking) and elucidation (uncloaking) process and need
to be used inside your `Model.afterSave` or `Model.beforeFind` events:

- `findObfuscated`: used to find records using an obfuscated (cloaked) primary key
- `findObfuscate`: used to obfuscate (cloak) all primary keys in a find result set

### findObfuscated

Use this finder if you want to look up a record using an obfuscated id.
The plugin will elucidate (uncloak) the obfuscated id and will execute the find
using the "normal" primary key as it is used inside your database.

CakePHP example:
```php
public function beforeFind()
{
    $this->Articles->find('obfuscated')
        ->where(['id' => 'S']); // will search for id 1
}
```

CRUD example:
```php
public function view()
{
    $this->Crud->on('beforeFind', function (Event $event) {
        $event->subject()->query->find('obfuscated');
}
```

#### findObfuscate

Use this finder if you want the plugin to obfuscate all "normal" primary keys
found in a find result set.

CakePHP example:
```php
public function beforeFind()
{
    $this->Articles->find('obfuscate');
}
```

CRUD example:
```php
public function index()
{
    $this->Crud->on('beforePaginate', function (Event $event) {
        $event->subject()->query->find('obfuscate');
}
```

### Methods

Attaching the behavior also makes the following two methods
available on the table:

- `obfuscate(string $str)`
- `elucidate(string $str)`

## Pro tips

### Authentication

A fairly common use case is applying obfuscation to user ids. To ensure
AuthComponent properly handles obfuscated ids specify the `obfuscated` finder
in your `authenticate` configuration settings like shown below:

```php
'authenticate' => [
     'ADmad/JwtAuth.Jwt' => [
        'finder' => 'obfuscated', // will use passed id `S` to search for record id 1
        'userModel' => 'Users',
        'fields' => [
            'username' => 'id'
        ],
        'parameter' => 'token'
    ]
]
```

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
