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

    public function testPublishComplete()
    {
        $complete = new PublishComplete();
        $complete->setIdentifier(11);

        $expected = implode([
            chr(112),
            chr(2),
            chr(0), chr(11),
        ]);

        $this->assertSerialisedPacketEquals($expected, $complete->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishComplete', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(11, $packet->getIdentifier());
    }

    public function testPublishReceive()
    {
        $received = new PublishReceived();
        $received->setIdentifier(12);

        $expected = implode([
            chr(80),
            chr(2),
            chr(0), chr(12),
        ]);

        $this->assertSerialisedPacketEquals($expected, $received->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishReceived', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(12, $packet->getIdentifier());
    }

    public function testPublishRelease()
    {
        $publishRelease = new PublishRelease();
        $publishRelease->setIdentifier(13);

        $expected = implode([
            chr(98),
            chr(2),
            chr(0), chr(13),
        ]);

        $this->assertSerialisedPacketEquals($expected, $publishRelease->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\PublishRelease', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(13, $packet->getIdentifier());
    }

    public function testSubscribe()
    {
        $subscribe = new Subscribe();
        $subscribe->setIdentifier(14);
        $subscribe->addSubscription('test', 2);

        $expected = implode([
            chr(130),
            chr(9),
            chr(0), chr(14),
            chr(0), chr(4),
            'test',
            chr(2),
        ]);

        $this->assertSerialisedPacketEquals($expected, $subscribe->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Subscribe', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(14, $packet->getIdentifier());
    }

    public function testSubscribeAck()
    {
        $ack = new SubscribeAck();
        $ack->setIdentifier(15);

        $expected = implode([
            chr(144),
            chr(2),
            chr(0), chr(15),
        ]);

        $this->assertSerialisedPacketEquals($expected, $ack->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\SubscribeAck', $packet);

        $this->assertSerialisedPacketEquals($expected, $packet->get());

        $this->assertEquals(15, $packet->getIdentifier());
    }

    public function testUnsubscribe()
    {
        $unsubscribe = new Unsubscribe();
        $unsubscribe->setIdentifier(16);
        $unsubscribe->removeSubscription('test');

        $expected = implode([
            chr(160),
            chr(8),
            chr(0), chr(16),
            chr(0), chr(4),
            'test',
        ]);
        $this->assertSerialisedPacketEquals($expected, $unsubscribe->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\Unsubscribe', $packet);

        $this->assertEquals(16, $packet->getIdentifier());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testUnsubscribeAck()
    {
        $ack = new UnsubscribeAck();
        $ack->setIdentifier(17);

        $expected = implode([
            chr(176),
            chr(2),
            chr(0), chr(17),
        ]);

        $this->assertSerialisedPacketEquals($expected, $ack->get());

        $packet = Parser::parse($expected);

        $this->assertInstanceOf('Drmer\Mqtt\Packet\UnsubscribeAck', $packet);

        $this->assertEquals(17, $packet->getIdentifier());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
