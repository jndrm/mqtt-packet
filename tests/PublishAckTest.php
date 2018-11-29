<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishAck;

class PublishAckTest extends TestCase {

    public function testPublishAckControlPacketTypeIsFour()
    {
        $this->assertEquals(4, PublishAck::getControlPacketType());
    }

    public function testParse()
    {
        $expected = implode([
            chr(64),
            chr(2),
            chr(0),
            chr(12),
        ]);
        $packet = new PublishAck();
        $packet->parse($expected);
        $this->assertEquals(12, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
