<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;

/**
 *
 * @covers \Drmer\Mqtt\Packet\Protocol\Version4
 *
 */
class Version4Test extends TestCase {
    public function testProtocolIdentifier()
    {
        $version = new Version4();

        $this->assertEquals('MQTT', $version->getProtocolIdentifierString());
    }

    public function testProtocolVersion()
    {
        $version = new Version4();

        $this->assertEquals(4, $version->getProtocolVersion());
    }
}
