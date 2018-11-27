<?php

namespace Drmer\Mqtt\Packet;

/**
 * A SUBACK Packet is sent by the Server to the Client to confirm receipt
 * and processing of a SUBSCRIBE Packet.
 */
class SubscribeAck extends ControlPacket
{
    public static function getControlPacketType()
    {
        return ControlPacketType::SUBACK;
    }
}
