<?php
declare(strict_types=1);

namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;
use PHPUnit\Framework\TestCase;

use Cake\TestSuite\TestCase;
use Muffin\Obfuscate\Model\Behavior\Strategy\TinyStrategy;

class TinyStrategyTest extends TestCase
{
    public function setUp(): void
    {
        $this->strategy = new TinyStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate(): void
    {
        $this->assertEquals('S', $this->strategy->obfuscate(1));
    }

    public function testElucidate(): void
    {
        $this->assertEquals(1, $this->strategy->elucidate('S'));
    }
}
