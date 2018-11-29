<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Utils\Parser;
use Drmer\Mqtt\Packet\Connect;
use Drmer\Mqtt\Packet\PublishAck;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class ParserTest extends TestCase {
    public function testConnect()
    {
        $connect = new Connect([
            'clientId' => 'abc',
        ]);
        $packet = Parser::parse($connect->get());

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Connect', $packet);

        $this->assertSerialisedPacketEquals($connect->get(), $packet->get());
    }

    public function testPublishAck()
    {
        $publishAck = new PublishAck();
        $packet = Parser::parse($publishAck->get());

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishAck', $packet);
        $this->assertSerialisedPacketEquals($publishAck->get(), $packet->get());
    }
}
