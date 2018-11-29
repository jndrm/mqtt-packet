<?php

namespace Drmer\Mqtt\Packet;

/**
 * A PUBREL Packet is the response to a PUBREC Packet.
 * It is the third packet of the QoS 2 protocol exchange.
 */
class PublishRelease extends ControlPacket
{
    public static function getControlPacketType()
    {
        return ControlPacketType::PUBREL;
    }

    protected function addReservedBitsToFixedHeaderControlPacketType($byte1)
    {
        return $byte1 + 2;
    }
}
