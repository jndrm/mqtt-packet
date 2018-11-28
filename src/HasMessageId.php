<?php

namespace Drmer\Mqtt\Packet;

trait HasMessageId {
    protected $messageId;

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function parse($rawInput)
    {
        $this->parseMessageId($rawInput);
    }

    public function parseMessageId($rawInput, $startPos=2)
    {
        if (strlen($rawInput) < $startPos + 2) {
            return;
        }
        $messageId = unpack('n', substr($rawInput, $startPos, 2));
        $this->messageId = array_pop($messageId);
    }

    protected function getVariableHeader()
    {
        return pack('n', $this->messageId);
    }
}
