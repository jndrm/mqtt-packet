<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\UnsubscribeAck;

class UnsubscribeAckTest extends TestCase {

    public function testUnsubscribeControlPacketTypeIsEleven()
    {
        $this->assertEquals(11, UnsubscribeAck::getControlPacketType());
    }

    public function testParse()
    {
        $expected = implode([
            chr(176),
            chr(2),
            chr(0), chr(17),
        ]);

        $packet = new UnsubscribeAck();
        $packet->parse($expected);

        $this->assertEquals(17, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
