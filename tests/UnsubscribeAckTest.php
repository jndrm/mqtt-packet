<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\UnsubscribeAck;

class UnsubscribeAckTest extends TestCase {

    public function testUnsubscribeControlPacketTypeIsEleven()
    {
        $this->assertEquals(11, UnsubscribeAck::getControlPacketType());
    }
}
