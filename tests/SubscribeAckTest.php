<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\SubscribeAck;
use Drmer\Mqtt\Packet\Protocol\Version4;

class SubscribeAckTest extends TestCase {

    public function testGetControlPacketType()
    {
        $packet = new SubscribeAck();
        $this->assertEquals(
            SubscribeAck::getControlPacketType(),
            9
        );
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new SubscribeAck();
        $this->assertEquals(
            substr($packet->get(), 0, 2),
            chr(9 << 4) . chr(0)
        );
    }

    public function testParse()
    {
        $expected = implode([
            chr(144),
            chr(2),
            chr(0), chr(15),
        ]);

        $packet = new SubscribeAck();
        $packet->parse($expected);

        $this->assertEquals(15, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
