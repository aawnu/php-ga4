<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\EventType;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;

class ConvertHelper
{
    /**
     * Converts unix or microtime to microseconds; 1 second = 1,000,000 microseconds
     *
     * @param int|float $unixOrMicro time() or microtime(true)
     *
     * @return int
     * @throws Ga4Exception
     */
    public static function timeAsMicro(int|float $unixOrMicro)
    {
        $secondAsMicro = 1_000_000;

        $input = strlen(intval($unixOrMicro));
        $current = strlen(time()) + 1;

        if ($input > $current) {
            throw Ga4Exception::throwMicrotimeInvalid($unixOrMicro);
        }

        return intval($secondAsMicro * $unixOrMicro);
    }

    /**
     * @param string $input
     * @return string snake_case
     */
    public static function snake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param string $input
     * @return string CamelCase
     */
    public static function camel(string $input): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    /**
     * Convert array of events into events models
     *
     * @param array<int,array<int,array<string,array<string>>>> $list [ ['eventname' => [ paramname => value ] ] ]
     *
     * @return array
     */
    public static function parseEvents(array $list): array
    {
        $events = [];

        $eventPrefix = "AlexWestergaard\\PhpGa4\\Event\\";

        foreach ($list as $packet) {
            foreach ($packet as $name => $params) {
                $event = $eventPrefix . $name;
                if (!class_exists($event)) {
                    continue;
                }

                $event = $event::fromArray($params);
                if (!($event instanceof EventType)) {
                    continue;
                }

                $events[] = $event;
            }
        }
        return $events;
    }
}
