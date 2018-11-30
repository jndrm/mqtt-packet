<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Subscribe;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class SubscribeTest extends TestCase
{
    public function testSubscribeControlPacketTypeIsEight()
    {
        $this->assertEquals(8, Subscribe::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new Subscribe();

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 0);

        $this->assertSerialisedPacketEquals(
            chr(130) . chr(8),
            substr($packet->get(), 0, 2)
        );
    }

    public function testGetHeaderTestFixedHeaderWithTwoSubscribedTopics()
    {
        $packet = new Subscribe();
        $packet->setIdentifier(12);

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 1);

        $subscriptionTopic = 'c/d';
        $packet->addSubscription($subscriptionTopic, 2);

        $expected = implode([
            chr(130),
            chr(14),
            chr(0), chr(12),
            chr(0), chr(3),
            'a/b',
            chr(1),
            chr(0), chr(3),
            'c/d',
            chr(2),
        ]);

        $this->assertEquals(12, $packet->getIdentifier());

        $this->assertSerialisedPacketEquals(
            chr(130) . chr(14),
            substr($packet->get(), 0, 2)
        );

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testParse()
    {
        $expected = implode([
            chr(130),
            chr(9),
            chr(0), chr(14),
            chr(0), chr(4),
            'test',
            chr(2),
        ]);

        $packet = new Subscribe();
        $packet->parse($expected);

        $this->assertEquals(14, $packet->getIdentifier());
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
