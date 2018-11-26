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

    public function testExceptionIsThrownForUnexpectedPacketType()
    {
        $input =
            chr(0b00000000) .
            chr(2) .
            chr(0) .
            chr(0);

        $this->expectException(
            'RuntimeException',
            'raw input is not valid for this control packet'
        );

        PingResponse::parse(new Version4(), $input);
    }

    public function testPacketCanBeParsed()
    {
        $version = new Version4();
        $expectedPacket = new PingResponse($version);

        $input =
            chr(0b11010000) .
            chr(2) .
            chr(0) .
            chr(0);

        $parsedPacket = PingResponse::parse($version, $input);

        $this->assertEquals($expectedPacket, $parsedPacket);
    }
}
