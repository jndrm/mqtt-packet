<?php

namespace Drmer\Mqtt\Packet\Protocol;

class Version4 implements Version {

    function getProtocolIdentifierString()
    {
        return 'MQTT';
    }

    /** @return int */
    function getProtocolVersion()
    {
        return 4;
    }
}
