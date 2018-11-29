<?php

namespace Drmer\Mqtt\Packet\Utils;

use Drmer\Mqtt\Packet\Protocol\Version;
use Drmer\Mqtt\Packet\Protocol\Version4;
use Drmer\Mqtt\Packet\ControlPacketType;
use Drmer\Mqtt\Packet\Connect;
use Drmer\Mqtt\Packet\ConnectionAck;
use Drmer\Mqtt\Packet\Publish;
use Drmer\Mqtt\Packet\PublishAck;
use Drmer\Mqtt\Packet\PublishReceived;
use Drmer\Mqtt\Packet\PublishRelease;
use Drmer\Mqtt\Packet\PublishComplete;
use Drmer\Mqtt\Packet\Subscribe;
use Drmer\Mqtt\Packet\SubscribeAck;
use Drmer\Mqtt\Packet\Unsubscribe;
use Drmer\Mqtt\Packet\UnsubscribeAck;
use Drmer\Mqtt\Packet\PingRequest;
use Drmer\Mqtt\Packet\PingResponse;
use Drmer\Mqtt\Packet\Disconnect;

final class Parser {
    public static function parse($rawInput, Version $version=null)
    {
        $packet = static::detectPacket(ord($rawInput{0}) >> 4);
        if (null == $packet) {
            return null;
        }
        if (null == $version) {
            $version = new Version4();
        }
        $packet->setVersion($version);
        $packet->parse($rawInput);
        return $packet;
    }

    private static function detectPacket($type)
    {
        switch ($type) {
            case ControlPacketType::CONNECT:
                return new Connect();
            case ControlPacketType::CONNACK:
                return new ConnectionAck();
            case ControlPacketType::PUBLISH:
                return new Publish();
            case ControlPacketType::PUBACK:
                return new PublishAck();
            case ControlPacketType::PUBREC:
                return new PublishReceived();
            case ControlPacketType::PUBREL:
                return new PublishRelease();
            case ControlPacketType::PUBCOMP:
                return new PublishComplete();
            case ControlPacketType::SUBSCRIBE:
                return new Subscribe();
            case ControlPacketType::SUBACK:
                return new SubscribeAck();
            case ControlPacketType::UNSUBSCRIBE:
                return new Unsubscribe();
            case ControlPacketType::UNSUBACK:
                return new UnsubscribeAck();
            case ControlPacketType::PINGREQ:
                return new PingRequest();
            case ControlPacketType::PINGRESP:
                return new PingResponse();
            case ControlPacketType::DISCONNECT:
                return new Disconnect();
            default:
                return null;
        }
    }

    public static function getCmd($type)
    {
        switch ($type) {
            case ControlPacketType::CONNECT:
                return 'connect';
            case ControlPacketType::CONNACK:
                return 'connack';
            case ControlPacketType::PUBLISH:
                return 'publish';
            case ControlPacketType::PUBACK:
                return 'puback';
            case ControlPacketType::PUBREC:
                return 'pubrec';
            case ControlPacketType::PUBREL:
                return 'pubrel';
            case ControlPacketType::PUBCOMP:
                return 'pubcomp';
            case ControlPacketType::SUBSCRIBE:
                return 'subscribe';
            case ControlPacketType::SUBACK:
                return 'suback';
            case ControlPacketType::UNSUBSCRIBE:
                return 'unsubscribe';
            case ControlPacketType::UNSUBACK:
                return 'unsuback';
            case ControlPacketType::PINGREQ:
                return 'pingreq';
            case ControlPacketType::PINGRESP:
                return 'pingresp';
            case ControlPacketType::DISCONNECT:
                return 'disconnect';
            default:
                return 'unknow';
        }
    }
}
