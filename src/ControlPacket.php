<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;
use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\Utils\MessageHelper;

abstract class ControlPacket {

    // packet identifer index
    // set this to -1 if subclass need to
    // proccess identifier itself.
    const ID_INDEX = 2;

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

    public function setIdentifier($identifier)
    {
        $this->identifier = intval($identifier);
        return $this;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function parse($rawInput)
    {
        // before parsing we need to clean payload
        $this->payload = '';
        if (static::ID_INDEX > 0) {
            $this->identifier = $this->parseIdentifier($rawInput, static::ID_INDEX);
        }
    }

    public function parseIdentifier($rawInput, $startIndex)
    {
        if (strlen($rawInput) < $startIndex + 2) {
            return null;
        }
        $identifier = unpack('n', substr($rawInput, $startIndex, 2));
        return array_pop($identifier);
    }

    /** @return int */
    public static function getControlPacketType()
    {
        throw new \RuntimeException('you must overwrite getControlPacketType()');
    }

    public function getPayload()
    {
        return $this->payload;
    }

    protected function getRemainingLength()
    {
        return strlen($this->getVariableHeader()) + strlen($this->getPayload());
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
        if (is_null($this->identifier)) {
            return '';
        }
        return pack('n', $this->identifier);
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

    /**
     * Read string from buffer.
     * @param $buffer
     * @return string
     * @see https://github.com/walkor/mqtt/blob/master/src/Protocols/Mqtt.php#readString
     */
    public static function readString(&$buffer) {
        $tmp = unpack('n', $buffer);
        $length = array_pop($tmp);
        if ($length + 2 > strlen($buffer)) {
            throw new \RuntimeException("buffer:".bin2hex($buffer)." lenth:$length not enough for unpackString");
        }

        $string = substr($buffer, 2, $length);
        $buffer = substr($buffer, $length + 2);
        return $string;
    }

    public function debugPrint()
    {
        echo "\n";
        echo MessageHelper::getReadableByRawString($this->get());
    }

    /**
     * Read unsigned short int from buffer.
     * @param $buffer
     * @return mixed
     * @see https://github.com/walkor/mqtt/blob/master/src/Protocols/Mqtt.php#readShortInt
     */
    public static function readShortInt(&$buffer) {
        $tmp = unpack('n', $buffer);
        $buffer = substr($buffer, 2);
        return array_pop($tmp);
    }
}
