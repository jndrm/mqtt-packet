<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;
use Drmer\Mqtt\Packet\Protocol\Version4;

/**
 * After a Network Connection is established by a Client to a Server, the
 * first Packet sent from the Client to the Server MUST be a CONNECT Packet.
 */
class Connect extends ControlPacket {

    /** @var null|string */
    protected $clientId = null;

    /** @var null|string  */
    protected $username = null;

    /** @var null|string  */
    protected $password = null;

    /** @var bool  */
    protected $cleanSession = true;

    /** @var string|null  */
    protected $willTopic;

    /** @var string|null  */
    protected $willMessage;

    /** @var bool|null  */
    protected $willQos;

    /** @var null */
    protected $willRetain;

    /** @var int */
    private $keepAlive;

    /**
     * @param Version $version
     * @param ConnectionOptions|Array|null $opts
     */
    public function __construct($opts=null)
    {
        parent::__construct();
        $options = $opts;
        if (is_array($opts)) {
            $options = new ConnectionOptions($opts);
        }
        if ($options) {
            $this->clientId = $options->clientId;
            $this->username = $options->username;
            $this->password = $options->password;
            $this->cleanSession = boolval($options->cleanSession);
            $this->willTopic = $options->willTopic;
            $this->willMessage = $options->willMessage;
            $this->willQos = boolval($options->willQos);
            $this->willRetain = $options->willRetain;
            $this->keepAlive = $options->keepAlive;
        }
        $this->buildPayload();
    }

    protected function buildPayload()
    {
        $this->addLengthPrefixedField($this->getClientId());
        if (!is_null($this->willTopic) && !is_null($this->willMessage)) {
            $this->addLengthPrefixedField($this->willTopic);
            $this->addLengthPrefixedField($this->willMessage);
        }
        if (!empty($this->username)) {
            $this->addLengthPrefixedField($this->username);
        }
        if (!empty($this->password)) {
            $this->addLengthPrefixedField($this->password);
        }
    }

    /**
     * @return int
     */
    public static function getControlPacketType()
    {
        return ControlPacketType::CONNECT;
    }

    /**
     * @return string
     */
    protected function getVariableHeader()
    {
        return chr(ControlPacketType::MOST_SIGNIFICANT_BYTE)              // byte 1
             . chr(strlen($this->version->getProtocolIdentifierString())) // byte 2
             . $this->version->getProtocolIdentifierString()              // byte 3,4,5,6
             . chr($this->version->getProtocolVersion())                  // byte 7
             . chr($this->getConnectFlags())                              // byte 8
             . $this->getKeepAlive();                                     // byte 9,10
    }

    /**
     * @return string
     */
    private function getKeepAlive()
    {
        $msb = $this->keepAlive >> 8;
        $lsb = $this->keepAlive % 256;

        return chr($msb)
             . chr($lsb);
    }

    /**
     * @return int
     */
    protected function getConnectFlags()
    {
        $connectByte = 0;
        if ($this->cleanSession) {
            $connectByte += 1 << 1;
        }
        if (!is_null($this->willTopic) && !is_null($this->willMessage)) {
            $connectByte += 1 << 2;
        }

        if ($this->willQos) {
            $connectByte += 1 << 3;
            // 4 TODO ?
        }

        if ($this->willRetain) {
            $connectByte += 1 << 5;
        }

        if (!empty($this->password)) {
            $connectByte += 1 << 6;
        }

        if (!empty($this->username)) {
            $connectByte += 1 << 7;
        }
        return $connectByte;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        if (is_null($this->clientId)) {
            $this->clientId = md5(microtime());
        }
        return substr($this->clientId, 0, 23);
    }
}
