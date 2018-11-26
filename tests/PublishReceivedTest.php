<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishReceived;

class PublishReceivedTest extends TestCase {

    public function testGetControlPacketType()
    {
        $this->assertEquals(
            PublishReceived::getControlPacketType(),
            5
        );
    }
}
