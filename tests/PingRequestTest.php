<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\PingRequest;

class PingRequestTest extends TestCase {

    public function testPingRequestControlPacketTypeIsTwelve()
    {
        $this->assertEquals(12, PingRequest::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $version = new Version4();
        $packet = new PingRequest($version);

        $this->assertEquals(
            substr($packet->get(), 0, 2),
            chr(12 << 4) . chr(0)
        );
    }
}
