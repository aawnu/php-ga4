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
        $parts = explode('.', trim($session), 3);

        $version = $parts[0] ?? null;
        $domainLevel = $parts[1] ?? null;
        $data = $parts[2] ?? null;

        // If the data part is empty, return an empty array
        if (!$data) {
            return [];
        }

        if ($version === 'GS1') {
            $model = explode('.', $data);

            return array_filter([
                'version' => $version,
                'domain_level' => $domainLevel,
                'session_id' => $model[0] ?? null,
                'session_number' => $model[1] ?? null,
                'session_engagement' => $model[2] ?? null,
                'timestamp' => $model[3] ?? null,
                'join_timer' => $model[4] ?? null,
                'logged_in_state' => $model[5] ?? null,
                'join_id' => $model[6] ?? null,
            ], fn ($val) => $val !== null && $val !== '');
        }

        if ($version !== 'GS2') {
            return [];
        }

        $cookieParts = explode('$', $data);

        if (empty(array_filter($cookieParts))) {
            return [];
        }

        $result = [];
        foreach ($cookieParts as $part) {
            if ($part === '') {
                continue;
            }

            $key = $part[0];
            $value = substr($part, 1);

            if ($value === '') {
                continue;
            }

            $mappedKey = match ($key) {
                's' => 'session_id',
                't' => 'timestamp',
                'o' => 'session_number',
                'g' => 'session_engaged',
                'j' => 'join_timer',
                'l' => 'logged_in_state',
                'h' => 'user_id',
                'd' => 'join_id',
                default => $key
            };

            $result[$mappedKey] = $value;
        }

        if (count($result) < 1) {
            return [];
        }

        return array_replace([
            'version' => $version,
            'domain_level' => $domainLevel,
        ], $result);
    }

    /**
     * Parse _ga/_gid style cookie values into Measurement Protocol client_id format.
     *
     * @param string $cookie
     * @return string|null
     */
    public static function parseClientIdFromGaCookie(string $cookie): ?string
    {
        $cookie = trim($cookie);
        if ($cookie === '') {
            return null;
        }

        $match = [];
        if (!preg_match('/^GA\d+\.\d+\.(\d+)\.(\d+)$/', $cookie, $match)) {
            return null;
        }

        return "{$match[1]}.{$match[2]}";
    }

    /**
     * Parse client id from cookie map, preferring _ga and then _gid.
     *
     * @param array<string,mixed> $cookies
     * @return string|null
     */
    public static function parseClientIdFromCookies(array $cookies): ?string
    {
        foreach (['_ga', '_gid'] as $name) {
            $cookie = $cookies[$name] ?? null;
            if (!is_scalar($cookie)) {
                continue;
            }

            $clientId = static::parseClientIdFromGaCookie(strval($cookie));
            if ($clientId !== null) {
                return $clientId;
            }
        }

        return null;
    }

    /**
     * Parse common GA cookies into a structure that can be mapped directly to event/client fields.
     *
     * @param array<string,mixed> $cookies
     * @return array<string,mixed>
     */
    public static function parseGaCookies(array $cookies): array
    {
        $result = [];

        $clientId = static::parseClientIdFromCookies($cookies);
        if ($clientId !== null) {
            $result['client_id'] = $clientId;
        }

        foreach ($cookies as $key => $value) {
            if (!is_string($key) || !str_starts_with($key, '_ga_') || !is_scalar($value)) {
                continue;
            }

            $session = static::parseSessionCookie(strval($value));
            if (count($session) < 1) {
                continue;
            }

            $result['session_cookie_name'] = $key;
            $result['session'] = $session;
            break;
        }

        return $result;
    }
}
