<?php

namespace Drmer\Mqtt\Packet\Protocol;

class Version4 implements Version
{
    public function getProtocolIdentifierString()
    {
        return 'MQTT';
    }

    /** @return int */
    public function getProtocolVersion()
    {
        return 4;
    }
}
