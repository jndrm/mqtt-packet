<?php

namespace Drmer\Mqtt\Packet\Protocol;

use Exception;

/**
 * The client received a packet of data that violates the MQTT protocol.
 */
final class Violation extends Exception {}
