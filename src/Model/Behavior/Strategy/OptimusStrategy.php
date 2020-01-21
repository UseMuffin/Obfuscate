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
    public function __construct($prime, $inverse, $random)
    {
        $this->_optimus = new Optimus($prime, $inverse, $random);
    }

    /**
     * {@inheritdoc}
     *
     * @param int|string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return (string)$this->_optimus->encode((int)$str);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to elucidate.
     * @return string
     */
    public function elucidate($str)
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return (string)$this->_optimus->decode((int)$str);
    }
}
