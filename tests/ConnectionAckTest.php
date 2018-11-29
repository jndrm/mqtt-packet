<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\ConnectionAck;
use Drmer\Mqtt\Packet\Protocol\Version4;

class ConnectionAckTest extends TestCase
{
    public function testPingResponseControlPacketTypeIsThirteen()
    {
        $packetType = ConnectionAck::getControlPacketType();

        $this->assertEquals(2, $packetType);
    }

    public function testParse()
    {
        $expected = implode([
            chr(32),
            chr(2),
            chr(0), chr(5),
        ]);

        $packet = new ConnectionAck();
        $packet->parse($expected);

        $this->assertEquals(5, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
