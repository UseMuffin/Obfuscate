<?php
namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Muffin\Obfuscate\Model\Behavior\Strategy\HashidStrategy;

class HashidStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B');
    }

    public function testObfuscate()
    {
        $result = $this->assertEquals('k8', $this->strategy->obfuscate(1));
    }

    public function testElucidate()
    {
        $this->assertEquals(1, $this->strategy->elucidate('k8'));
    }

    public function testMinLength()
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B', 10);

        $this->assertEquals('qxPAk8pnOV', $this->strategy->obfuscate(1));
    }

    public function testCustomAlphabet()
    {
        $this->strategy = new HashidStrategy('5SX0TEjkR1mLOw8Gvq2VyJxIFhgCAYidrclDWaM3so9bfzZpuUenKtP74QNH6B', 0, 'abcdefghijklmnopqrstuvwxyz');

        $this->assertEquals('vg', $this->strategy->obfuscate(1));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Missing salt for Hashid strategy
     */
    public function testSaltException()
    {
        new HashidStrategy();
    }
}
