<?php

namespace Drmer\Mqtt\Packet\Protocol;

interface Version {

    /** @return string */
    function getProtocolIdentifierString();

    /** @return int */
    function getProtocolVersion();
}
