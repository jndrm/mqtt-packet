<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Disconnect;

class DisconnectTest extends TestCase {

    public function testDisconnectControlPacketTypeIsFourteen()
    {
        $this->assertEquals(14, Disconnect::getControlPacketType());
    }
}
