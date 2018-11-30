<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Disconnect;

class DisconnectTest extends TestCase
{
    public function testDisconnectControlPacketTypeIsFourteen()
    {
        $this->assertEquals(14, Disconnect::getControlPacketType());
    }

    public function testParse()
    {
        $expected = implode([
            chr(224),
            chr(2),
            chr(0), chr(5),
        ]);

        $packet = new Disconnect();
        $packet->parse($expected);

        $this->assertEquals(5, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
