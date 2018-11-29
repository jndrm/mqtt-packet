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

    public function testConnectAck()
    {
        $conAck = new ConnectionAck();
        $conAck->setIdentifier(10);
        $packet = Parser::parse($conAck->get());

        $this->assertInstanceOf('Drmer\Mqtt\Packet\ConnectionAck', $packet);

        $this->assertSerialisedPacketEquals($conAck->get(), $packet->get());
    }

    public function testDisconnect()
    {
        $disconnect = new Disconnect();

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
        $ping = new PingRequest();

        $expected = implode([
            chr(192),
            chr(0),
        ]);

        $this->assertSerialisedPacketEquals($expected, $ping->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PingRequest', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPingResponse()
    {
        $pong = new PingResponse();

        $expected = implode([
            chr(208),
            chr(0),
        ]);

        $this->assertSerialisedPacketEquals($expected, $pong->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PingResponse', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublish()
    {
        $publish = new Publish();
        $publish->setIdentifier(10);
        $publish->setTopic('test');
        $publish->setPayload('hello');

        $expected = implode([
            chr(48),
            chr(11),
            chr(0), chr(4),
            'test',
            'hello',
        ]);

        $this->assertSerialisedPacketEquals($expected, $publish->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals('test', $packet->getTopic());
        $this->assertEquals('hello', $packet->getPayload());
    }

    public function testPublishAck()
    {
        $publishAck = new PublishAck();
        $packet = Parser::parse($publishAck->get());

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishAck', $packet);
        $this->assertSerialisedPacketEquals($publishAck->get(), $packet->get());
    }
}
