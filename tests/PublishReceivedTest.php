<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishReceived;

class PublishReceivedTest extends TestCase
{
    public function testGetControlPacketType()
    {
        $this->assertEquals(
            PublishReceived::getControlPacketType(),
            5
        );
    }

    public function testParse()
    {
        $expected = implode([
            chr(80),
            chr(2),
            chr(0), chr(12),
        ]);

        $packet = new PublishReceived();
        $packet->parse($expected);

        $this->assertEquals(12, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
