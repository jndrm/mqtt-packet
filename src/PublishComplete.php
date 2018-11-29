<?php

namespace Drmer\Mqtt\Packet;

/**
 * The PUBCOMP Packet is the response to a PUBREL Packet.
 * It is the fourth and final packet of the QoS 2 protocol exchange.
 */
class PublishComplete extends ControlPacket {
    public static function getControlPacketType()
    {
        return ControlPacketType::PUBCOMP;
    }
}
