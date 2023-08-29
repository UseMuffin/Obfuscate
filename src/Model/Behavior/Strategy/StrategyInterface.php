<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Model\Behavior\Strategy;

/**
 * Interface StrategyInterface
 */
interface StrategyInterface
{
    /**
     * Obfuscates a given integer/string.
     *
     * @param string|int $str String to obfuscate.
     * @return string
     */
    public function obfuscate(string|int $str): string;

    /**
     * Elucidates (de-obfuscates) a given string.
     *
     * @param string|int $str String to elucidate.
     * @return int
     */
    public function elucidate(string|int $str): int;
}
