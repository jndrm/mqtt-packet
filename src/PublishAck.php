<?php

namespace Drmer\Mqtt\Packet;

/**
 * A PUBACK Packet is the response to a PUBLISH Packet with QoS level 1.
 */
class PublishAck extends ControlPacket {
    public static function getControlPacketType()
    {
        return ControlPacketType::PUBACK;
    }
}
