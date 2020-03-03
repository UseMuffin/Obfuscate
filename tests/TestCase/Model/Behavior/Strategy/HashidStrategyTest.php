<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Exception;
use Muffin\Obfuscate\Model\Behavior\Strategy\HashidStrategy;
use PHPUnit\Framework\TestCase;

class HashidStrategyTest extends TestCase
{
    /**
     * @var \Muffin\Obfuscate\Model\Behavior\Strategy\HashidStrategy;
     */
    public $strategy;

    public function setUp(): void
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate(): void
    {
        $this->assertEquals('k8', $this->strategy->obfuscate(1));
    }

    public function testElucidate(): void
    {
        $this->assertEquals(1, $this->strategy->elucidate('k8'));
    }

    public function testMinLength(): void
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B', 10);

        $this->assertEquals('qxPAk8pnOV', $this->strategy->obfuscate(1));
    }

    public function testCustomAlphabet(): void
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B', 0, 'abcdefghijklmnopqrstuvwxyz');

        $this->assertEquals('vg', $this->strategy->obfuscate(1));
    }

    public function testSaltException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing salt for Hashid strategy');
        new HashidStrategy();
    }
}
