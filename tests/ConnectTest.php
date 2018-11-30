<?php

namespace Drmer\Tests\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\Connect;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

class ConnectTest extends TestCase
{
    public function testConnectControlPacketTypeIsOne()
    {
        $this->assertEquals(1, Connect::getControlPacketType());
    }

    public function testGetHeaderTestFixedHeader()
    {
        $packet = new Connect([
            'clientId' => 'clientid',
        ]);

        $this->assertSerialisedPacketEquals(
            chr(1 << 4) . chr(20),
            substr($packet->get(), 0, 2)
        );
    }

    public function testGetHeaderTestVariableHeaderWithoutConnectFlags()
    {
        $packet = new Connect([
            'clientid' => 'clientid',
            'cleanSession' => false,
        ]);
        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(0),    // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagsCleanSession()
    {
        $packet = new Connect();

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(2),    // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
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

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(4),    // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagWillRetain()
    {
        $packet = new Connect([
            'clientId' => 'clientId',
            'cleanSession' => false,
            'willRetain' => true,
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(32),   // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagUsername()
    {
        $packet = new Connect([
            'username' => 'username',
            'clientId' => 'clientId',
            'cleanSession' => false,
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(128),  // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagPassword()
    {
        $packet = new Connect([
            'password' => 'password',
            'clientId' => 'clientId',
            'cleanSession' => false,
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(64),   // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagWillWillQos()
    {
        $packet = new Connect([
            'clientId' => 'clientId',
            'cleanSession' => false,
            'willQos' => true,
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(8),    // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testGetHeaderTestVariableHeaderWithConnectFlagUserNamePasswordCleanSession()
    {
        $packet = new Connect([
            'username' => 'username',
            'password' => 'password',
            'clientId' => 'clientId',
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(194),  // byte 8
            chr(0),    // byte 9
            chr(0),    // byte 10
        ]);
        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
        );
    }

    public function testBytesNineAndTenOfVariableHeaderAreKeepAlive()
    {
        $packet = new Connect([
            'keepAlive' => 999,
        ]);

        $expected = implode([
            chr(0),    // byte 1
            chr(4),    // byte 2
            'MQTT',    // byte 3,4,5,6
            chr(4),    // byte 7
            chr(2),    // byte 8
            chr(3),    // byte 9
            chr(231),  // byte 10
        ]);

        $this->assertSerialisedPacketEquals(
            $expected,
            substr($packet->get(), 2, 10)
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

    public function testHeaderWithQos1()
    {
        $packet = new Connect([
            'willTopic' => 'test',
            'willQos' => 1,
            'clientId' => '',
        ]);
        $expected = implode([
            chr(16),
            chr(20),
            chr(0), chr(4),
            'MQTT',
            chr(4),
            chr(14),
            chr(0), chr(0), chr(0), chr(0),
            chr(0), chr(4),
            'test',
            chr(0), chr(0),
        ]);
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testHeaderWithQos2()
    {
        $packet = new Connect([
            'willTopic' => 'test',
            'willQos' => 2,
            'clientId' => '',
        ]);
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
        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }

    public function testParse()
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
        $packet = new Connect();
        $packet->parse($expected);

        $this->assertEquals('', $packet->getClientId());
        $this->assertEquals('test', $packet->getWillTopic());
        $this->assertEquals(2, $packet->getWillQos());

        $this->assertSerialisedPacketEquals($expected, $packet->get());
    }
}
