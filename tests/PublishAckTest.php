<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishAck;

class PublishAckTest extends TestCase {

    public function testPublishAckControlPacketTypeIsFour()
    {
        $this->assertEquals(4, PublishAck::getControlPacketType());
    }
}
