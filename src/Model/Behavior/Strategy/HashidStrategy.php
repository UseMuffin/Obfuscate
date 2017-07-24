<?php
namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Cake\Core\Configure;
use Hashids\Hashids;

/**
 * Class DefaultStrategy
 *
 */
class HashidStrategy implements StrategyInterface
{

    protected $_hashid;

    /**
     * Random alpha-numeric set where each character must only be
     * used exactly once.
     *
     * @var string
     */
    protected $_salt;

    /**
     * The minimum hash length.
     *
     * @var int
     */
    protected $_minLength;

    /**
     * Custom alphabet to use.
     *
     * @var string
     */
    protected $_alphabet;

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
        $this->_salt = $salt;
        $this->_minLength = $minLength;
        $this->_alphabet = $alphabet;

        if (!is_string($alphabet)) {
            $this->_hashid = new Hashids($salt, $minLength);
        } else {
            $this->_hashid = new Hashids($salt, $minLength, $alphabet);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $str String to obfuscate.
     * @return string
     */
    public function obfuscate($str)
    {
        return $this->_hashid->encode($str);
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
