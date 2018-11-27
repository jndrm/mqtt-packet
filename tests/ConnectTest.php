<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\Connect;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class ConnectTest extends TestCase {

    public function testConnectControlPacketTypeIsOne()
    {
        $this->assertEquals(1, Connect::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new Connect([
            'clientId' => 'clientid',
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(chr(1 << 4) . chr(20)),
            MessageHelper::getReadableByRawString(substr($packet->get(), 0, 2))
        );
    }

    public function testGetHeaderTestVariableHeaderWithoutConnectFlags()
    {
        $packet = new Connect([
            'clientid' => 'clientid',
            'cleanSession' => false,
        ]);
        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(0) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagsCleanSession()
    {
        $packet = new Connect();

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(2) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagWillFlag()
    {
        $packet = new Connect([
            'clientId' => 'clientId',
            'cleanSession' => false,
            'willTopic' => 'willTopic',
            'willMessage' => 'willMessage',
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(4) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagWillRetain()
    {
        $packet = new Connect([
            'clientId' => 'clientId',
            'cleanSession' => false,
            'willRetain' => true,
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(32) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagUsername()
    {
        $packet = new Connect([
            'username' => 'username',
            'clientId' => 'clientId',
            'cleanSession' => false,
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(128) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagPassword()
    {
        $packet = new Connect([
            'password' => 'password',
            'clientId' => 'clientId',
            'cleanSession' => false,
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(64) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagWillWillQos()
    {
        $packet = new Connect([
            'clientId' => 'clientId',
            'cleanSession' => false,
            'willQos' => true,
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(8) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagUserNamePasswordCleanSession()
    {
        $packet = new Connect([
            'username' => 'username',
            'password' => 'password',
            'clientId' => 'clientId',
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(194) .    // byte 8
                chr(0) .    // byte 9
                chr(0)      // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testBytesNineAndTenOfVariableHeaderAreKeepAlive()
    {
        $packet = new Connect([
            'keepAlive' => 999,
        ]);

        $this->assertEquals(
            MessageHelper::getReadableByRawString(
                chr(0) .    // byte 1
                chr(4) .    // byte 2
                'MQTT' .    // byte 3,4,5,6
                chr(4) .    // byte 7
                chr(2) .    // byte 8
                chr(3) .    // byte 9
                chr(231)    // byte 10
            ),
            MessageHelper::getReadableByRawString(substr($packet->get(), 2, 10))
        );
    }

    public function testGetHeaderTestPayloadClientId()
    {
        $packet = new Connect([
            'clientId' => 'clientid',
        ]);

        $this->assertEquals(
            substr($packet->get(), 12),
            chr(0) .    // byte 1
            chr(8) .    // byte 2
            'clientid'
        );
    }
}
