<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use Cake\Core\Configure;
use Exception;
use Hashids\Hashids;

/**
 * Class HashidStrategy
 */
class HashidStrategy implements StrategyInterface
{
    /**
     * Obfuscator.
     *
     * @var \Hashids\Hashids
     */
    protected Hashids $_hashid;

    /**
     * Constructor.
     *
     * @param string|null $salt Random alpha-numeric set.
     * @param int $minLength The minimum hash length.
     * @param string|null $alphabet Custom alphabet to use.
     * @throws \Exception
     */
    public function __construct(?string $salt = null, int $minLength = 0, ?string $alphabet = null)
    {
        if ($salt === null) {
            $salt = Configure::read('Obfuscate.salt');
        }
        if (empty($salt)) {
            throw new Exception('Missing salt for Hashid strategy');
        }

        if ($alphabet === null) {
            $this->_hashid = new Hashids($salt, $minLength);
        } else {
            $this->_hashid = new Hashids($salt, $minLength, $alphabet);
        }
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(int|string $str): string
    {
        return $this->_hashid->encode((string)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(int|string $str): int
    {
        return current($this->_hashid->decode((string)$str));
    }
}
