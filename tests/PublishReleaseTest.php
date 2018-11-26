<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishRelease;

class PublishReleaseTest extends TestCase {

    public function testGetControlPacketType()
    {
        $this->assertEquals(
            PublishRelease::getControlPacketType(),
            6
        );
    }
}
