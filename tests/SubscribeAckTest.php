<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\SubscribeAck;
use Drmer\Mqtt\Packet\Protocol\Version4;

class SubscribeAckTest extends TestCase {

    public function testGetControlPacketType()
    {
        $version = new Version4();
        $packet = new SubscribeAck($version);
        $this->assertEquals(
            SubscribeAck::getControlPacketType(),
            9
        );
    }

    public function testGetHeaderTestFixedHeader()
    {
        $version = new Version4();
        $packet = new SubscribeAck($version);
        $this->assertEquals(
            substr($packet->get(), 0, 2),
            chr(9 << 4) . chr(0)
        );
    }

}
