<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Subscribe;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class SubscribeTest extends TestCase {

    public function testSubscribeControlPacketTypeIsEight()
    {
        $this->assertEquals(8, Subscribe::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new Subscribe();

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 0);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(substr($packet->get(), 0, 2)),
            MessageHelper::getReadableByRawString(chr(130) . chr(8))
        );
    }

    public function testGetHeaderTestFixedHeaderWithTwoSubscribedTopics()
    {
        $packet = new Subscribe();

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 1);

        $subscriptionTopic = 'c/d';
        $packet->addSubscription($subscriptionTopic, 2);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(substr($packet->get(), 0, 2)),
            MessageHelper::getReadableByRawString(chr(130) . chr(14))
        );
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
