<?php

namespace Drmer\Tests\Mqtt\Packet;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class TestCase extends BaseTestCase {
    protected function assertSerialisedPacketEquals($expected, $actual)
    {
        $this->assertEquals(
            MessageHelper::getReadableByRawString($expected),
            MessageHelper::getReadableByRawString($actual)
        );
    }
}
