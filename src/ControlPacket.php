<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;
use Drmer\Mqtt\Packet\Protocol\Version4;

abstract class ControlPacket {

    /** @var $version Version */
    protected $version;

    protected $payload = '';

    protected $identifier;

    public function __construct()
    {
        $this->version = new Version4();
    }

    public function setVersion(Version $version)
    {
        $this->version = $version;
    }

    public function parse($rawInput)
    {
    }

    /** @return int */
    public static function getControlPacketType() {
        throw new \RuntimeException('you must overwrite getControlPacketType()');
    }

    protected function getPayloadLength()
    {
        return strlen($this->getPayload());
    }

    public function getPayload()
    {
        return $this->payload;
    }

    protected function getRemainingLength()
    {
        return strlen($this->getVariableHeader()) + $this->getPayloadLength();
    }

    /**
     * @return string
     */
    protected function getFixedHeader()
    {
        // Figure 3.8
        $byte1 = static::getControlPacketType() << 4;
        $byte1 = $this->addReservedBitsToFixedHeaderControlPacketType($byte1);

        $byte2 = $this->getRemainingLength();

        return chr($byte1)
             . chr($byte2);
    }

    /**
     * @return string
     */
    protected function getVariableHeader()
    {
        return '';
    }

    /**
     * @param $stringToAdd
     */
    public function addRawToPayLoad($stringToAdd)
    {
        $this->payload .= $stringToAdd;
    }

    /**
     * @param $fieldPayload
     */
    public function addLengthPrefixedField($fieldPayload)
    {
        $return = $this->getLengthPrefixField($fieldPayload);
        $this->addRawToPayLoad($return);
    }

    public function getLengthPrefixField($fieldPayload)
    {
        $stringLength = strlen($fieldPayload);
        $msb = $stringLength >> 8;
        $lsb = $stringLength % 256;
        $return = chr($msb);
        $return .= chr($lsb);
        $return .= $fieldPayload;

        return $return;
    }

    public function get()
    {
        return $this->getFixedHeader() .
               $this->getVariableHeader() .
               $this->getPayload();
    }

    /**
     * @param $byte1
     * @return $byte1 unmodified
     */
    protected function addReservedBitsToFixedHeaderControlPacketType($byte1)
    {
        return $byte1;
    }

    /**
     * @param int $startIndex
     * @param string $rawInput
     * @return string
     */
    protected static function getPayloadLengthPrefixFieldInRawInput($startIndex, $rawInput)
    {
        $headerLength = 2;
        $header = substr($rawInput, $startIndex, $headerLength);
        $lengthOfMessage = ord($header{1});

        return substr($rawInput, $startIndex + $headerLength, $lengthOfMessage);
    }
}
