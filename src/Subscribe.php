<?php

namespace Drmer\Mqtt\Packet;

/**
 * The SUBSCRIBE Packet is sent from the Client to the Server to create
 * one or more Subscriptions.
 */
class Subscribe extends ControlPacket {

    protected $topicFilters = array();

    public static function getControlPacketType()
    {
        return ControlPacketType::SUBSCRIBE;
    }

    protected function addReservedBitsToFixedHeaderControlPacketType($byte1)
    {
        return $byte1 + 2;
    }

    /**
     * @return string
     */
    protected function getVariableHeader()
    {
        return pack('n', $this->identifier);
    }

    /**
     * @param string $topic
     * @param int $qos
     */
    public function addSubscription($topic, $qos = 0)
    {
        $this->payload .= $this->getLengthPrefixField($topic);
        $this->payload .= chr($qos);
    }

    public function parse($rawInput)
    {
        parent::parse($rawInput);
        $body = substr($rawInput, 4);
        $topic = static::readString($body);
        $qos = ord($body[0]) & 0x3;
        $this->addSubscription($topic, $qos);
    }
}
