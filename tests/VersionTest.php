<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;

/**
 *
 * @covers \Drmer\Mqtt\Packet\Protocol\Version
 *
 */
class VersionTest extends TestCase
{
    public function testExistance()
    {
        $this->assertTrue(interface_exists('Drmer\Mqtt\Packet\Protocol\Version'));
    }
}
