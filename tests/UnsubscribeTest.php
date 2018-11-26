<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Unsubscribe;

class UnsubscribeTest extends TestCase {

    public function testUnsubscribeControlPacketTypeIsTen()
    {
        $this->assertEquals(10, Unsubscribe::getControlPacketType());
    }
}
