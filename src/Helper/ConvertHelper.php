<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Facade\Type\EventType;

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

    /**
     * Parse the session cookie (GA_{measurement_id}) into named parts.
     *
     * @param string $session The cookie value
     * @return array
     */
    public static function parseSessionCookie(string $session): array
    {
        $parts = explode('.', $session);
        
        $version = $parts[0] ?? null;
        $k = $parts[1] ?? null;
        $data = $parts[2] ?? null;

        // If the data part is empty, return an empty array
        if (!$data) {
            return [];
        }

        if ($version === 'GS1') {
            $data = explode('.', $session);

            return [
                'version' => $data[0] ?? null,
                'domain_level' => $data[1] ?? null,
                'session_id' => $data[2] ?? null,
                'session_number' => $data[3] ?? null,
                'session_engagement' => $data[4] ?? null,
                'timestampt' => $data[5] ?? null
            ];
        }

        $cookieParts = explode('$', $data);

        if (empty(array_filter($cookieParts))) {
            return [];
        }

        $data = array_map(
            fn ($part) => match ($part[0]) {
                's' => ['session_id' => $part],
                't' => ['timestamp' => $part],
                'o' => ['session_number' => $part],
                'g' => ['session_engaged' => $part],
                'j' => ['join_timer' => $part],
                'l' => ['logged_in_state' => $part],
                'h' => ['user_id' => $part],
                'd' => ['join_id' => $part],
                default => [$part[0] => $part]
            },
            $cookieParts
        );

        $result = [];

        foreach ($data as $mapValue) {
            foreach ($mapValue as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
