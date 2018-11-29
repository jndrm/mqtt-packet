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

    public function testParse()
    {
        $expected = implode([
            chr(98),
            chr(2),
            chr(0), chr(13),
        ]);

        $packet = new PublishRelease();
        $packet->parse($expected);

        $this->assertEquals(13, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
