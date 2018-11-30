<?php

namespace Drmer\Mqtt\Packet;

/**
 * The SUBSCRIBE Packet is sent from the Client to the Server to create
 * one or more Subscriptions.
 */
class Subscribe extends ControlPacket
{
    protected $topicFilters = [];

    protected $topics = [];

    protected $identifier = 0;

    public static function getControlPacketType()
    {
        return ControlPacketType::SUBSCRIBE;
    }

    protected function addReservedBitsToFixedHeaderControlPacketType($byte1)
    {
        return $byte1 + 2;
    }

    /**
     * @param string $topic
     * @param int $qos
     */
    public function addSubscription($topic, $qos = 0)
    {
        $this->topics[$topic] = $qos;
    }

    public function getPayload()
    {
        $this->payload = '';
        foreach ($this->topics as $topic => $qos) {
            $this->payload .= $this->getLengthPrefixField($topic);
            $this->payload .= chr($qos);
        }
        return $this->payload;
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
