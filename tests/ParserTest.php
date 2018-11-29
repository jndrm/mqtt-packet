<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Utils\Parser;
use Drmer\Mqtt\Packet\Connect;
use Drmer\Mqtt\Packet\ConnectionAck;
use Drmer\Mqtt\Packet\Disconnect;
use Drmer\Mqtt\Packet\PingRequest;
use Drmer\Mqtt\Packet\PingResponse;
use Drmer\Mqtt\Packet\Publish;
use Drmer\Mqtt\Packet\PublishAck;
use Drmer\Mqtt\Packet\PublishComplete;
use Drmer\Mqtt\Packet\PublishReceived;
use Drmer\Mqtt\Packet\PublishRelease;
use Drmer\Mqtt\Packet\Subscribe;
use Drmer\Mqtt\Packet\SubscribeAck;
use Drmer\Mqtt\Packet\Unsubscribe;
use Drmer\Mqtt\Packet\UnsubscribeAck;

class ParserTest extends TestCase {
    public function testConnect()
    {
        $expected = implode([
            chr(16),
            chr(20),
            chr(0), chr(4),
            'MQTT',
            chr(4),
            chr(22),
            chr(0), chr(0), chr(0), chr(0),
            chr(0), chr(4),
            'test',
            chr(0), chr(0),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Connect', $packet);

        $this->assertEquals('test', $packet->getWillTopic());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testConnectAck()
    {
        $expected = implode([
            chr(32),
            chr(2),
            chr(0), chr(5),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\ConnectionAck', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testDisconnect()
    {
        $expected = implode([
            chr(224),
            chr(0),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Disconnect', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPingRequest()
    {
        $expected = implode([
            chr(192),
            chr(0),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PingRequest', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPingResponse()
    {
        $expected = implode([
            chr(208),
            chr(0),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PingResponse', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublish()
    {
        $expected = implode([
            chr(48),
            chr(11),
            chr(0), chr(4),
            'test',
            'hello',
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals('test', $packet->getTopic());
        $this->assertEquals('hello', $packet->getPayload());
    }

    public function testPublishAck()
    {
        $expected = implode([
            chr(64),
            chr(2),
            chr(0),
            chr(12),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishAck', $packet);
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishComplete()
    {
        $expected = implode([
            chr(112),
            chr(2),
            chr(0), chr(11),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishComplete', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(11, $packet->getIdentifier());
    }

    public function testPublishReceive()
    {
        $expected = implode([
            chr(80),
            chr(2),
            chr(0), chr(12),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishReceived', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(12, $packet->getIdentifier());
    }

    public function testPublishRelease()
    {
        $expected = implode([
            chr(98),
            chr(2),
            chr(0), chr(13),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishRelease', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(13, $packet->getIdentifier());
    }

    public function testSubscribe()
    {
        $expected = implode([
            chr(130),
            chr(9),
            chr(0), chr(14),
            chr(0), chr(4),
            'test',
            chr(2),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Subscribe', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(14, $packet->getIdentifier());
    }

    public function testSubscribeAck()
    {
        $expected = implode([
            chr(144),
            chr(2),
            chr(0), chr(15),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\SubscribeAck', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(15, $packet->getIdentifier());
    }

    public function testUnsubscribe()
    {
        $expected = implode([
            chr(160),
            chr(8),
            chr(0), chr(16),
            chr(0), chr(4),
            'test',
        ]);
        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Unsubscribe', $packet);

        $this->assertEquals(16, $packet->getIdentifier());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testUnsubscribeAck()
    {
        $expected = implode([
            chr(176),
            chr(2),
            chr(0), chr(17),
        ]);

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\UnsubscribeAck', $packet);

        $this->assertEquals(17, $packet->getIdentifier());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
