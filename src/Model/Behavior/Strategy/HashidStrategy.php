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
     * Constructor.
     *
     * @param string $salt Random alpha-numeric set.
     */
    public function __construct($salt = null)
    {
        if ($salt === null) {
            $salt = Configure::read('Obfuscate.salt');
        }
        if (empty($salt)) {
            throw new \Exception('Missing salt for Hashid strategy');
        }
        $this->_salt = $salt;
        $this->_hashid = new Hashids($salt);
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
