<?php

namespace Drmer\Mqtt\Packet;

/**
 * An UNSUBSCRIBE Packet is sent by the Client to the Server, to
 * unsubscribe from topics.
 */
class Unsubscribe extends ControlPacket {

    public static function getControlPacketType()
    {
        return ControlPacketType::UNSUBSCRIBE;
    }

    /**
     * @param string $topic
     */
    public function removeSubscription($topic)
    {
        $this->payload .= $this->getLengthPrefixField($topic);
    }
}
