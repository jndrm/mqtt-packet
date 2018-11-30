<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\PingRequest;

class PingRequestTest extends TestCase
{
    public function testPingRequestControlPacketTypeIsTwelve()
    {
        $this->assertEquals(12, PingRequest::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new PingRequest();

        $this->assertEquals(
            substr($packet->get(), 0, 2),
            chr(12 << 4) . chr(0)
        );
    }

    public function testParse()
    {
        $expected = implode([
            chr(192),
            chr(0),
        ]);
        $ping = new PingRequest();
        $ping->parse($expected);

        $this->assertSerialisedPacketEquals($expected, $ping->get());
    }
}
