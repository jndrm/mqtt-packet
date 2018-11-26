<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Subscribe;
use Drmer\Mqtt\Packet\MessageHelper;

class SubscribeTest extends TestCase {

    public function testSubscribeControlPacketTypeIsEight()
    {
        $this->assertEquals(8, Subscribe::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $version = new \Drmer\Mqtt\Packet\Protocol\Version4();
        $packet = new Subscribe($version);

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 0);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(substr($packet->get(), 0, 2)),
            MessageHelper::getReadableByRawString(chr(130) . chr(8))
        );
    }

    public function testGetHeaderTestFixedHeaderWithTwoSubscribedTopics()
    {
        $version = new \Drmer\Mqtt\Packet\Protocol\Version4();
        $packet = new Subscribe($version);

        $subscriptionTopic = 'a/b';
        $packet->addSubscription($subscriptionTopic, 1);

        $subscriptionTopic = 'c/d';
        $packet->addSubscription($subscriptionTopic, 2);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(substr($packet->get(), 0, 2)),
            MessageHelper::getReadableByRawString(chr(130) . chr(14))
        );
    }
}
