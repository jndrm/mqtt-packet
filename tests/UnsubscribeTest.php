<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Unsubscribe;

class UnsubscribeTest extends TestCase
{
    public function testUnsubscribeControlPacketTypeIsTen()
    {
        $this->assertEquals(10, Unsubscribe::getControlPacketType());
    }

    public function testParse()
    {
        $expected = implode([
            chr(160),
            chr(8),
            chr(0), chr(16),
            chr(0), chr(4),
            'test',
        ]);

        $packet = new Unsubscribe();
        $packet->parse($expected);

        $this->assertEquals(16, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
