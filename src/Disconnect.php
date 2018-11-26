<?php

namespace Drmer\Mqtt\Packet;

/**
 * The DISCONNECT Packet is the final Control Packet sent from the Client
 * to the Server. It indicates that the Client is disconnecting cleanly.
 */
class Disconnect extends ControlPacket
{
    public static function getControlPacketType()
    {
        return ControlPacketType::DISCONNECT;
    }
}
