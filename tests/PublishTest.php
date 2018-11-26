<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\MessageHelper;
use Drmer\Mqtt\Packet\Publish;
use Drmer\Mqtt\Packet\Protocol\Version4;

/**
 * See http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718038
 * for packet type, QoS, DUP and retain.
 *
 * See http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718039
 * for topic and packet identifier.
 */
class PublishTest extends TestCase {

    public function testPublishStandard()
    {
        $this->assertEquals(3, Publish::getControlPacketType());
    }

    public function testExceptionIsThrownForUnexpectedPacketType()
    {
        $input =
            chr(0b00100000) .
            chr(2) .
            chr(0) .
            chr(0);

        $this->expectException(
            'RuntimeException',
            'raw input is not valid for this control packet'
        );

        Publish::parse(new Version4(), $input);
    }

    public function testPublishStandardWithQos0()
    {
        $packet = new Publish(new Version4());
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
        $packet = new Publish(new Version4());
        $packet->setQos(1);
        $packet->setTopic('test');
        $packet->setMessageId(1);

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
        $packet = new Publish(new Version4());
        $packet->setQos(2);
        $packet->setTopic('test');
        $packet->setMessageId(1);

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
        $packet = new Publish(new Version4());
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
        $packet = new Publish(new Version4());
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
        $packet = new Publish(new Version4());
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
        $packet = new Publish(new Version4());

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

        $packet = new Publish(new Version4());
        $return = $packet->setTopic($topic);
        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $return);
    }

    public function testSetMessageIdReturn()
    {
        $messageId = 1;

        $packet = new Publish(new Version4());
        $return = $packet->setMessageId($messageId);
        $this->assertInstanceOf('Drmer\Mqtt\Packet\Publish', $return);
    }

    public function qosProvider() {
        return array(
            array(0, 0b00110000),
            array(1, 0b00110010),
            array(2, 0b00110100),
        );
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
        $parsedPacket = Publish::parse(new Version4(), $input);

        $comparisonPacket = new Publish(new Version4());
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
        $parsedPacket = Publish::parse(new Version4(), $input);

        $comparisonPacket = new Publish(new Version4());
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
        $parsedPacket = Publish::parse(new Version4(), $input);

        $comparisonPacket = new Publish(new Version4());
        $comparisonPacket->setDup(true);

        $this->assertPacketEquals($comparisonPacket, $parsedPacket);
    }

    public function testParseWithTopic()
    {
        $expectedPacket = new Publish(new Version4());
        $expectedPacket->setTopic('some/test/topic');

        $input =
            chr(0b00110000) .
            chr(17) .
            chr(0) .
            chr(15) .
            'some/test/topic';
        $parsedPacket = Publish::parse(new Version4(), $input);

        $this->assertPacketEquals($expectedPacket, $parsedPacket);
        $this->assertEquals('some/test/topic', $parsedPacket->getTopic());
    }

    public function testParseWithPayload()
    {
        $expectedPacket = new Publish(new Version4());
        $expectedPacket->addRawToPayLoad('My payload');

        $input =
            chr(0b00110000) .
            chr(12) .
            chr(0) .
            chr(0) .
            'My payload';
        $parsedPacket = Publish::parse(new Version4(), $input);

        $this->assertPacketEquals($expectedPacket, $parsedPacket);
        $this->assertEquals('My payload', $parsedPacket->getPayload());
    }

    private function assertPacketEquals(Publish $expected, Publish $actual)
    {
        $this->assertEquals($expected, $actual);
        $this->assertSerialisedPacketEquals($expected->get(), $actual->get());
    }

    private function assertSerialisedPacketEquals($expected, $actual)
    {
        $this->assertEquals(
            MessageHelper::getReadableByRawString($expected),
            MessageHelper::getReadableByRawString($actual)
        );
    }
}
