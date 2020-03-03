<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Cake\Core\Configure;
use Hashids\Hashids;

/**
 * Class DefaultStrategy
 *
 */
class HashidStrategy implements StrategyInterface
{
    /**
     * Obfuscator.
     *
     * @var \Hashids\Hashids
     */
    protected $_hashid;

    /**
     * Constructor.
     *
     * @param string $salt Random alpha-numeric set.
     * @param int $minLength The minimum hash length.
     * @param string $alphabet Custom alphabet to use.
     * @throws \Exception
     */
    public function __construct($salt = null, $minLength = 0, $alphabet = null)
    {
        if ($salt === null) {
            $salt = Configure::read('Obfuscate.salt');
        }
        if (empty($salt)) {
            throw new \Exception('Missing salt for Hashid strategy');
        }

        if ($alphabet === null) {
            $this->_hashid = new Hashids($salt, $minLength);
        } else {
            $this->_hashid = new Hashids($salt, $minLength, $alphabet);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param int|string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        return $this->_hashid->encode((string)$str);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to elucidate.
     * @return string
     */
    public function elucidate($str)
    {
        return current($this->_hashid->decode($str));
    }
}
