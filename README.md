# Obfuscate

[![Build Status](https://img.shields.io/travis/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://travis-ci.org/UseMuffin/Obfuscate)
[![Coverage](https://img.shields.io/coveralls/UseMuffin/Obfuscate/master.svg?style=flat-square)](https://coveralls.io/r/UseMuffin/Obfuscate)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/obfuscate.svg?style=flat-square)](https://packagist.org/packages/muffin/obfuscate)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

CakePHP 3 support for ID obfuscation using any combination of HashIds, Optimus and/or Tiny.

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

### Enabling the behavior

First, you will need to choose one of the two (3) built-in strategies:

- `Hashids` which requires the [hashids/hashids](http://hashids.org/php/) package, or
- `OptimusStrategy` which requires the [jenssegers/optimus](https://github.com/jenssegers/optimus) package, or
- `TinyStrategy` which requires the [zackkitzmiller/tiny](https://github.com/zackkitzmiller/tiny-php/) package.

Once you have (composer) installed the required package, you are ready to set up
obfuscation.

In any table, add the behavior like so (example showing the `TinyStrategy`):

```php
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

// ...

$this->addBehavior('Muffin/Obfuscate.Obfuscate', [
    'strategy' => new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B')
]);
```

## Opt-in

Please be aware that the plugin is totally unobtrusive and will do absolutely
nothing unless you use one of the two (2) custom finders inside your
`Model.afterSave` or `Model.beforeFind` events:

- `findObfuscated`: use to find records using an obfuscated (cloaked) primary key
- `findObfuscate`: use to obfuscate (cloak) all primary keys in a find result set

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

### findObfuscate

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

Attaching the behavior also makes the following two (2) methods
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
        'finder' => 'obfuscated',
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
