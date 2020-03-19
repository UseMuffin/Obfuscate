<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use InvalidArgumentException;
use Jenssegers\Optimus\Optimus;

class OptimusStrategy implements StrategyInterface
{
    /**
     * Obfuscator.
     *
     * @var \Jenssegers\Optimus\Optimus
     */
    protected $_optimus;

    /**
     * Constructor to configure the `Jenssegers\Optimus\Optimus` library.
     *
     * @param int $prime Prime number.
     * @param int $inverse Inverse number.
     * @param int $random Random number.
     */
    public function __construct(int $prime, int $inverse, int $random)
    {
        $this->_optimus = new Optimus($prime, $inverse, $random);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate($str): string
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return (string)$this->_optimus->encode((int)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate($str): int
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return $this->_optimus->decode((int)$str);
    }
}
