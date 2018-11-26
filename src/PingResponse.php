<?php

namespace Drmer\Mqtt\Packet;

/**
 * A PINGRESP Packet is sent by the Server to the Client in response
 * to a PINGREQ Packet. It indicates that the Server is alive.
 */
class PingResponse extends ControlPacket
{
    const EVENT = 'PING_RESPONSE';

    public static function getControlPacketType()
    {
        return ControlPacketType::PINGRESP;
    }
}
