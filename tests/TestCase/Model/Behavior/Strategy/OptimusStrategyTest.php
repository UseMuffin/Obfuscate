<?php
namespace Muffin\Obfuscate\Test\TestCase\Model\Behavior\Strategy;

use Muffin\Obfuscate\Model\Behavior\Strategy\OptimusStrategy;

class OptimusStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->strategy = new OptimusStrategy(2123809381, 1885413229, 146808189);
    }

    public function testObfuscate()
    {
        $this->assertEquals(1985404696, $this->strategy->obfuscate(1));
    }

    public function testElucidate()
    {
        $this->assertEquals(1, $this->strategy->elucidate(1985404696));
    }

}
