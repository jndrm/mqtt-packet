<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Utils\MessageHelper;
use Drmer\Mqtt\Packet\Publish;
use Drmer\Mqtt\Packet\Protocol\Version4;

/**
 * See http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718038
 * for packet type, QoS, DUP and retain.
 *
 * See http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718039
 * for topic and packet identifier.
 */
class PublishTest extends TestCase
{
    public function testPublishStandard()
    {
        $this->assertEquals(3, Publish::getControlPacketType());
    }

    public function testPublishStandardWithQos0()
    {
        $packet = new Publish();
        $packet->setQos(0);
        $packet->setTopic('test');

        $expected = implode([
            chr(0b00110000),
            chr(6),
            chr(0), chr(4),
            'test',
        ]);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishStandardWithQos1()
    {
        $packet = new Publish();
        $packet->setQos(1);
        $packet->setTopic('test');
        $packet->setIdentifier(1);

        $expected = implode([
            chr(0b00110010),
            chr(8),
            chr(0), chr(4),
            'test',
            chr(0), chr(1),
        ]);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishStandardWithQos2()
    {
        $packet = new Publish();
        $packet->setQos(2);
        $packet->setTopic('test');
        $packet->setIdentifier(1);

        $expected = implode([
            chr(0b00110100),
            chr(8),
            chr(0), chr(4),
            'test',
            chr(0), chr(1),
        ]);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishStandardWithDup()
    {
        $packet = new Publish();
        $packet->setDup(true);

        $expected =
            chr(0b00111000) .
            chr(2) .
            chr(0) .
            chr(0);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishStandardWithRetain()
    {
        $packet = new Publish();
        $packet->setRetain(true);

        $expected =
            chr(0b00110001) .
            chr(2) .
            chr(0) .
            chr(0);

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testPublishWithPayload()
    {
        $packet = new Publish();
        $packet->addRawToPayLoad('This is the payload');

        $expected =
            chr(0b00110000) .
            chr(21) .
            chr(0) .
            chr(0) .
            'This is the payload';

        $this->assertEquals('This is the payload', $packet->getPayload());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testTopic()
    {
        $packet = new Publish();

        $packet->setTopic('topic/test');

        $expected =
            chr(0b00110000) .
            chr(12) .
            chr(0) .
            chr(10) .
            'topic/test';

        $this->assertEquals('topic/test', $packet->getTopic());

        $this->assertSerialisedPacketEquals(
            $expected,
            $packet->get()
        );
    }

    public function testSetTopicReturn()
    {
        $topic = 'topictest';

        $packet = new Publish();
        $return = $packet->setTopic($topic);
        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $return);
    }

    public function testSetMessageIdReturn()
    {
        $messageId = 1;

        $packet = new Publish();
        $return = $packet->setIdentifier($messageId);
        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $return);
    }

    public function qosProvider()
    {
        return [
            [0, 0b00110000],
            [1, 0b00110010],
            [2, 0b00110100],
        ];
    }

    /**
     * @dataProvider qosProvider
     */
    public function testParseWithQos($qos, $byte1)
    {
        $input =
            chr($byte1) .
            chr(2) .
            chr(0) .
            chr(0);
        $parsedPacket = $this->parse($input);

        $comparisonPacket = new Publish();
        $comparisonPacket->setQos($qos);

        $this->assertPacketEquals($comparisonPacket, $parsedPacket);
    }

    public function testParseWithRetain()
    {
        $input =
            chr(0b00110001) .
            chr(2) .
            chr(0) .
            chr(0);
        $parsedPacket = $this->parse($input);

        $comparisonPacket = new Publish();
        $comparisonPacket->setRetain(true);

        $this->assertPacketEquals($comparisonPacket, $parsedPacket);
    }

    public function testParseWithDup()
    {
        $input =
            chr(0b00111000) .
            chr(2) .
            chr(0) .
            chr(0);
        $parsedPacket = $this->parse($input);

        $comparisonPacket = new Publish();
        $comparisonPacket->setDup(true);

        $this->assertPacketEquals($comparisonPacket, $parsedPacket);
    }

    private function parse($input)
    {
        $packet = new Publish();
        $packet->parse($input);
        return $packet;
    }

    public function testParseWithTopic()
    {
        $expectedPacket = new Publish();
        $expectedPacket->setTopic('some/test/topic');

        $input =
            chr(0b00110000) .
            chr(17) .
            chr(0) .
            chr(15) .
            'some/test/topic';
        $parsedPacket = $this->parse($input);

        $this->assertPacketEquals($expectedPacket, $parsedPacket);
        $this->assertEquals('some/test/topic', $parsedPacket->getTopic());
    }

    public function testParseWithPayload()
    {
        $expectedPacket = new Publish();
        $expectedPacket->addRawToPayLoad('My payload');

        $input =
            chr(0b00110000) .
            chr(12) .
            chr(0) .
            chr(0) .
            'My payload';
        $parsedPacket = $this->parse($input);

        $this->assertPacketEquals($expectedPacket, $parsedPacket);
        $this->assertEquals('My payload', $parsedPacket->getPayload());
    }

    public function testLongPayload()
    {
        $expectedPacket = new Publish();
        $topic = '00000000-0000-0000-0000-000000000000';
        $expectedPacket->setTopic($topic);
        $payload = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $expectedPacket->setPayload($payload);

        $input =
            chr(0b00110000) .
            chr(228) . chr(3) .
            chr(0) .
            chr(36) .
            $topic .
            $payload;

        $this->assertSerialisedPacketEquals($input, $expectedPacket->get());
        $parsedPacket = $this->parse($input);
        $this->assertPacketEquals($expectedPacket, $parsedPacket);
        $this->assertEquals($topic, $parsedPacket->getTopic());
        $this->assertEquals($payload, $parsedPacket->getPayload());
    }

    private function assertPacketEquals(Publish $expected, Publish $actual)
    {
        $this->assertEquals($expected, $actual);
        $this->assertSerialisedPacketEquals($expected->get(), $actual->get());
    }
}
