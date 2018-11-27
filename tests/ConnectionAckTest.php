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
}
