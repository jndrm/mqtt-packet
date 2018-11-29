<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\PingResponse;

/**
 * @covers \Drmer\Mqtt\Packet\PingResponse
 */
class PingResponseTest extends TestCase
{
    public function testPingResponseControlPacketTypeIsThirteen()
    {
        $packetType = PingResponse::getControlPacketType();

        $this->assertEquals(13, $packetType);
    }

    public function testParse()
    {
        $expected = implode([
            chr(208),
            chr(0),
        ]);
        $pong = new PingResponse();
        $pong->parse($expected);

        $this->assertSerialisedPacketEquals($expected, $pong->get());
    }
}
