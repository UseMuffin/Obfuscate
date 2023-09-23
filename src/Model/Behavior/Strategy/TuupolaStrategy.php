<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

use InvalidArgumentException;
use Tuupola\Base62;

/**
 * Class TuupolaStrategy
 */
class TuupolaStrategy implements StrategyInterface
{
    /**
     * Obfuscator.
     *
     * @var \Tuupola\Base62
     */
    private Base62 $tuupola;

    /**
     *  Constructor.
     *
     * @param string|null $set Random alpha-numeric set.
     */
    public function __construct(?string $set = null)
    {
        $options = $set ? ['characters' => $set] : [];
        $this->tuupola = new Base62($options);
    }

    /**
     * @inheritDoc
     */
    public function obfuscate(int|string $str): string
    {
        if (!is_numeric($str)) {
            throw new InvalidArgumentException('Argument should be an integer');
        }

        return $this->tuupola->encodeInteger((int)$str);
    }

    /**
     * @inheritDoc
     */
    public function elucidate(int|string $str): int
    {
        return $this->tuupola->decodeInteger((string)$str);
    }
}
