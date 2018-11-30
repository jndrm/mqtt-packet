<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\PublishComplete;

class PublishCompleteTest extends TestCase
{
    public function testGetControlPacketType()
    {
        $this->assertEquals(
            PublishComplete::getControlPacketType(),
            7
        );
    }

    public function testParse()
    {
        $expected = implode([
            chr(112),
            chr(2),
            chr(0), chr(11),
        ]);

        $packet = new PublishComplete();
        $packet->parse($expected);

        $this->assertEquals(11, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
