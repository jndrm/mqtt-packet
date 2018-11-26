<?php

namespace Drmer\Mqtt\Packet;

/**
 * The UNSUBACK Packet is sent by the Server to the Client to confirm
 * receipt of an UNSUBSCRIBE Packet.
 */
class UnsubscribeAck extends ControlPacket
{
    const EVENT = 'UNSUBSCRIBE_ACK';

    public static function getControlPacketType()
    {
        return ControlPacketType::UNSUBACK;
    }
}
