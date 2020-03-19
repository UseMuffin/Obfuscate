<?php
declare(strict_types=1);

namespace Muffin\Obfuscate;

use Cake\Core\BasePlugin;

/**
 * Plugin class for obfuscate
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'Obfuscate';

    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected $bootstrapEnabled = false;

    /**
     * Load routes or not
     *
     * @var bool
     */
    protected $routesEnabled = false;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected $consoleEnabled = false;
}
