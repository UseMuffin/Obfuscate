<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use InvalidArgumentException;
use Tuupola\Base62;

/**
 * Class Base62Strategy
 */
class Base62Strategy implements StrategyInterface
{
    /**
     * Obfuscator.
     *
     * @var \Tuupola\Base62
     */
    private Base62 $base62;

    /**
     *  Constructor.
     *
     * @param string|null $set Random alpha-numeric set.
     */
    public function __construct(?string $set = null)
    {
        $options = $set ? ['characters' => $set] : [];
        $this->base62 = new Base62($options);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(int|string $str): string
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return $this->base62->encodeInteger((int)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(int|string $str): int
    {
        return $this->base62->decodeInteger((string)$str);
    }
}
