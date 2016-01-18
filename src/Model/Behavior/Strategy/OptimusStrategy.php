<?php
namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Jenssegers\Optimus\Optimus;

class OptimusStrategy implements StrategyInterface
{
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
     * @param string $str String to obfuscate.
     * @return int
     */
    public function obfuscate($str)
    {
        return $this->_optimus->encode($str);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to elucidate.
     * @return int
     */
    public function elucidate($str)
    {
        return $this->_optimus->decode($str);
    }
}
