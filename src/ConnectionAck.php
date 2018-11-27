<?php

namespace Drmer\Mqtt\Packet;

/**
 * The CONNACK Packet is the packet sent by the Server in response to
 * a CONNECT Packet received from a Client.
 */
class ConnectionAck extends ControlPacket
{
    public static function getControlPacketType()
    {
        return ControlPacketType::CONNACK;
    }
}
