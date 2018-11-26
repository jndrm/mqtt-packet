<?php

namespace Drmer\Mqtt\Packet;

/**
 * A PUBREC Packet is the response to a PUBLISH Packet with QoS 2.
 * It is the second packet of the QoS 2 protocol exchange.
 */
class PublishReceived extends ControlPacket
{
    const EVENT = 'PUBLISH_RECEIVED';

    public static function getControlPacketType()
    {
        return ControlPacketType::PUBREC;
    }
}
