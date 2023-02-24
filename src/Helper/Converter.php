<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\Event;

class Converter
{
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
                if (!($event instanceof Event)) {
                    continue;
                }

                $events[] = $event;
            }
        }
        return $events;
    }
}
