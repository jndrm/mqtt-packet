<?php

namespace Drmer\Mqtt\Packet;

use Drmer\Mqtt\Packet\Protocol\Version;
use Drmer\Mqtt\Packet\Protocol\Violation as ProtocolViolation;

class Factory
{
    /**
     * @param Version $version
     * @param string $remainingData
     * @throws ProtocolViolation
     * @return ConnectionAck|PingResponse|SubscribeAck|Publish|PublishComplete|PublishRelease|PublishReceived|void
     */
    public static function getNextPacket(Version $version, $remainingData)
    {
        while(isset($remainingData{1})) {
            $remainingLength = ord($remainingData{1});
            $packetLength = 2 + $remainingLength;
            $nextPacketData = substr($remainingData, 0, $packetLength);
            $remainingData = substr($remainingData, $packetLength);

            yield self::getByMessage($version, $nextPacketData);
        }
    }

    private static function getByMessage(Version $version, $input)
    {
        $controlPacketType = ord($input{0}) >> 4;

        switch ($controlPacketType) {
            case ConnectionAck::getControlPacketType():
                return ConnectionAck::parse($version, $input);

            case PingResponse::getControlPacketType():
                return PingResponse::parse($version, $input);

            case SubscribeAck::getControlPacketType():
                return SubscribeAck::parse($version, $input);

            case Publish::getControlPacketType():
                return Publish::parse($version, $input);

            case PublishComplete::getControlPacketType():
                return PublishComplete::parse($version, $input);

            case PublishRelease::getControlPacketType():
                return PublishRelease::parse($version, $input);

            case PublishReceived::getControlPacketType():
                return PublishReceived::parse($version, $input);
        }

        throw new ProtocolViolation('Unexpected packet type: ' . $controlPacketType);
    }
}
