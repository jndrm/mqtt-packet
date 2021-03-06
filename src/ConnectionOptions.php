<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\QoS\Levels;

/**
 * Connection Options
 *
 * @author Alin Eugen Deac <ade@vestergaardcompany.com>
 * @package Mqtt\Packet
 */
class ConnectionOptions
{
    /**
     * Username
     *
     * Can be used by broker for authentication
     * and authorisation
     *
     * @var string
     */
    public $username = null;

    /**
     * Password
     *
     * Can be used by broker for authentication
     * and authorisation
     *
     * @var string
     */
    public $password = null;

    /**
     * Client Identifier
     *
     * Used by Clients and by Brokers to identify
     * state that they hold relating to this MQTT
     * Session between the Client and the Server
     *
     * @see http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Toc398718031
     *
     * @var string
     */
    public $clientId = null;

    /**
     * Clean session (flag)
     *
     * If false, broker will resume communication
     * based on previous state, which it remembers.
     *
     * If true, broker will not keep track of any
     * previous state.
     *
     * @var bool
     */
    public $cleanSession = true;

    /**
     * Will Topic
     *
     * In case a client disconnects ungracefully,
     * the broker will inform other clients
     * about this, by sending a message to
     * the provided topic.
     *
     * @var string
     */
    public $willTopic = null;

    /**
     * Will Message
     *
     * In case a client disconnects ungracefully,
     * the broker will inform other clients
     * about this, by sending a message to
     * the provided topic.
     *
     * @var string
     */
    public $willMessage = null;

    /**
     * Will Quality of Service
     *
     * @see http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Table_3.11_-
     *
     * @var int
     */
    public $willQos = 0;

    /**
     * Will Retain (flag)
     *
     * If false, the Broker MUST publish the Will Message as a non-retained message.
     * If true, the Broker MUST publish the Will Message as a retained message.
     *
     * @var bool
     */
    public $willRetain = false;

    /**
     * The Keep Alive is a time interval measured in seconds.
     *
     * @see http://docs.oasis-open.org/mqtt/mqtt/v3.1.1/os/mqtt-v3.1.1-os.html#_Keep_Alive
     *
     * @var int
     */
    public $keepAlive = 0;

    /**
     * ConnectionOptions constructor.
     *
     * @param array $options [optional]
     */
    public function __construct(array $options = [])
    {
        $this->populate($options);

        if (!is_null($this->willTopic)) {
            if (is_null($this->willMessage)) {
                $this->willMessage = '';
            }
        }
        if (is_null($this->clientId)) {
            $this->clientId = substr(md5(microtime()), 0, 23);
        } elseif ('' === $this->clientId && $this->cleanSession == false) {
            $this->cleanSession = true;
        }
    }

    /**
     * Populate these options from an array
     *
     * @param array $options [optional]
     */
    public function populate(array $options = [])
    {
        foreach ($options as $key => $value) {
            // NB: This can in the future be changed to automatically use setters and enforce validation
            $this->$key = $value;
        }
    }
}
