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
        $packet = null;
        switch (ord($rawInput{0}) >> 4) {
            case ControlPacketType::CONNECT:
                $packet = new Connect();
                break;
            case ControlPacketType::CONNACK:
                $packet = new ConnectionAck();
                break;
            case ControlPacketType::PUBLISH:
                $packet = new Publish();
                break;
            case ControlPacketType::PUBACK:
                $packet = new PublishAck();
                break;
            case ControlPacketType::PUBREC:
                $packet = new PublishReceived();
                break;
            case ControlPacketType::PUBREL:
                $packet = new PublishRelease();
                break;
            case ControlPacketType::PUBCOMP:
                $packet = new PublishComplete();
                break;
            case ControlPacketType::SUBSCRIBE:
                $packet = new Subscribe();
                break;
            case ControlPacketType::SUBACK:
                $packet = new SubscribeAck();
                break;
            case ControlPacketType::UNSUBSCRIBE:
                $packet = new Unsubscribe();
                break;
            case ControlPacketType::UNSUBACK:
                $packet = new UnsubscribeAck();
                break;
            case ControlPacketType::PINGREQ:
                $packet = new PingRequest();
                break;
            case ControlPacketType::PINGRESP:
                $packet = new PingResponse();
                break;
            case ControlPacketType::DISCONNECT:
                $packet = new Disconnect();
                break;
            default:
                return null;
        }
        if (null == $version) {
            $version = new Version4();
        }
        $packet->setVersion($version);
        $packet->parse($rawInput);
        return $packet;
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
