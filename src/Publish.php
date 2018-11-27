<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;

/**
 * A PUBLISH Control Packet is sent from a Client to a Server or from
 * Server to a Client to transport an Application Message.
 */
class Publish extends ControlPacket
{
    protected $messageId;

    protected $topic = '';

    protected $qos = 0;

    protected $dup = false;

    protected $retain = false;

    public static function getControlPacketType()
    {
        return ControlPacketType::PUBLISH;
    }

    public function parse($rawInput)
    {
        $topic = static::getPayloadLengthPrefixFieldInRawInput(2, $rawInput);
        $this->setTopic($topic);

        $byte1 = $rawInput{0};
        if (!empty($byte1)) {
            $this->setRetain(($byte1 & 1) === 1);
            if (($byte1 & 2) === 2) {
                $this->setQos(1);
            } elseif (($byte1 & 4) === 4) {
                $this->setQos(2);
            }
            $this->setDup(($byte1 & 8) === 8);
        }
        $this->payload = substr(
            $rawInput,
            4 + strlen($topic)
        );
    }

    /**
     * @param $topic
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @param $messageId
     * @return $this
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @param int $qos 0,1,2
     * @return $this
     */
    public function setQos($qos)
    {
        $this->qos = $qos;
        return $this;
    }

    /**
     * @param bool $dup
     * @return $this
     */
    public function setDup($dup)
    {
        $this->dup = $dup;
        return $this;
    }

    /**
     * @param bool $retain
     * @return $this
     */
    public function setRetain($retain)
    {
        $this->retain = $retain;
        return $this;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return int
     */
    public function getQos()
    {
        return $this->qos;
    }

    /**
     * @return string
     */
    protected function getVariableHeader()
    {
        return $this->getLengthPrefixField($this->topic);
    }

    protected function getRemainingLength()
    {
        return parent::getRemainingLength() + ($this->qos > 0 ? 2 : 0);
    }

    public function get()
    {
        return implode([
            $this->getFixedHeader(),
            $this->getVariableHeader(),
            ($this->qos > 0 ? pack('n', $this->messageId) : ''),
            $this->getPayload(),
        ]);
    }

    protected function addReservedBitsToFixedHeaderControlPacketType($byte1)
    {
        $qosByte = 0;
        if ($this->qos === 1) {
            $qosByte = 1;
        } else if ($this->qos === 2) {
            $qosByte = 2;
        }
        $byte1 += $qosByte << 1;

        if ($this->dup) {
            $byte1 += 8;
        }

        if ($this->retain) {
            $byte1 += 1;
        }

        return $byte1;
    }
}
