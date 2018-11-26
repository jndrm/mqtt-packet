<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishComplete;

class PublishCompleteTest extends TestCase {

    public function testGetControlPacketType()
    {
        $this->assertEquals(
            PublishComplete::getControlPacketType(),
            7
        );
    }

}
