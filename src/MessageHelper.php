<?php

namespace Drmer\Mqtt\Packet;

class MessageHelper {

    /**
     * @param $message
     * @return string
     */
    public static function getReadableByRawString($message)
    {
        $message = (string) $message;
        $return  = "+-----+------+-------+-----+\n";
        $return .= "| idx | byte | ascii | dec |\n";
        $return .= "+-----+------+-------+-----+\n";
        for ($index = 0, $lengthOfMessage = strlen($message); $index < $lengthOfMessage; $index++) {
            $return .= '| ' . str_pad($index, 4, ' ');
            $return .= '| ' . str_pad($index +1 , 5, ' ');
            $return .= '| ' . str_pad((ord($message{$index}) > 32 ? $message{$index} : ("(" . ord($message{$index})) . ")"), 6, ' ');
            $return .= '| ' . str_pad(ord($message{$index}), 4, ' ');
            $return .= "|\n";
        }
        $return .= "+-----+------+-------+-----+\n";
        return $return;
    }

}
