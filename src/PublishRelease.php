<?php

namespace Drmer\Mqtt\Packet;

/**
 * A PUBREL Packet is the response to a PUBREC Packet.
 * It is the third packet of the QoS 2 protocol exchange.
 */
class PublishRelease extends ControlPacket
{
    const EVENT = 'PUBLISH_RELEASE';

    public static function getControlPacketType()
    {
        return ControlPacketType::PUBREL;
    }
}
