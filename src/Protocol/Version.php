<?php

namespace Drmer\Mqtt\Packet\Protocol;

interface Version
{

    /** @return string */
    public function getProtocolIdentifierString();

    /** @return int */
    public function getProtocolVersion();
}
