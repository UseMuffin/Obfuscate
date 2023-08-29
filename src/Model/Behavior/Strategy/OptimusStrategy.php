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
    protected Optimus $_optimus;

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
    public function obfuscate(int|string $str): string
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return (string)$this->_optimus->encode((int)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(int|string $str): int
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return $this->_optimus->decode((int)$str);
    }
}
