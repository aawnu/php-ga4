<?php

namespace AlexWestergaard\PhpGa4;

use GuzzleHttp\Client as Guzzle;
use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;

/**
 * Analytics wrapper to contain UserProperties and Events to post on Google Analytics
 */
class Analytics extends Helper\IOHelper implements Facade\Type\AnalyticsType
{
    private Guzzle $guzzle;

    protected null|bool $non_personalized_ads = false;
    protected null|int $timestamp_micros;
    protected null|string $client_id;
    protected null|string $user_id;
    protected array $user_properties = [];
    protected array $events = [];

    public function __construct(
        private string $measurement_id,
        private string $api_secret,
        private bool $debug = false
    ) {
        parent::__construct();
        $this->guzzle = new Guzzle();
    }

    public function getParams(): array
    {
        return [
            'non_personalized_ads',
            'timestamp_micros',
            'client_id',
            'user_id',
            'user_properties',
            'events',
        ];
    }

    public function getRequiredParams(): array
    {
        $return = [];

        // Either client_id OR user_id MUST to be set
        if (
            (!isset($this->client_id) || empty($this->client_id))
            && (!isset($this->user_id) || empty($this->user_id))
        ) {
            $return[] = 'client_id';
        }

        return $return;
    }

    public function setNonPersonalizedAds(bool $exclude)
    {
        $this->non_personalized_ads = $exclude;
        return $this;
    }

    public function setClientId(string $id)
    {
        $this->client_id = $id;
        return $this;
    }

    public function setUserId(string $id)
    {
        $this->user_id = $id;
        return $this;
    }

    public function setTimestampMicros(int|float $microOrUnix)
    {
        $min = Helper\ConvertHelper::timeAsMicro(strtotime('-3 days') + 10);
        $max = Helper\ConvertHelper::timeAsMicro(time() + 3);

        $time = Helper\ConvertHelper::timeAsMicro($microOrUnix);

        if ($time < $min || $time > $max) {
            throw Ga4Exception::throwMicrotimeExpired();
        }

        $this->timestamp_micros = $time;
        return $this;
    }

    public function addUserProperty(Facade\Type\UserPropertyType ...$props)
    {
        foreach ($props as $prop) {
            $this->user_properties = array_replace($this->user_properties, $prop->toArray());
        }

        return $this;
    }

    public function addEvent(Facade\Type\EventType ...$events)
    {
        foreach ($events as $event) {
            $this->events[] = $event->toArray();
        }

        return $this;
    }

    public function post(): void
    {
        if (empty($this->measurement_id)) {
            throw Ga4Exception::throwMissingMeasurementId();
        }

        if (empty($this->api_secret)) {
            throw Ga4Exception::throwMissingApiSecret();
        }

        $url = $this->debug ? Facade\Type\AnalyticsType::URL_DEBUG : Facade\Type\AnalyticsType::URL_LIVE;
        $url .= '?' . http_build_query(['measurement_id' => $this->measurement_id, 'api_secret' => $this->api_secret]);

        $body = $this->toArray();

        $chunkUserProperties = array_chunk($this->user_properties, 25, true);
        $this->user_properties = [];

        $chunkEvents = array_chunk($this->events, 25);
        $this->events = [];

        $chunkMax = count($chunkEvents) > count($chunkUserProperties) ? count($chunkEvents) : count($chunkUserProperties);

        for ($chunk = 0; $chunk < $chunkMax; $chunk++) {
            $body['user_properties'] = $chunkUserProperties[$chunk] ?? [];
            if (empty($body['user_properties'])) {
                unset($body['user_properties']);
            }

            $body['events'] = $chunkEvents[$chunk] ?? [];
            if (empty($body['events'])) {
                unset($body['events']);
            }

            $kB = 1024;
            if (($size = mb_strlen(json_encode($body))) > ($kB * 130)) {
                Ga4Exception::throwRequestTooLarge(intval($size / $kB));
                continue;
            }

            $jsonBody = json_encode($body);
            $jsonBody = strtr($jsonBody, [':[]' => ':{}']);

            $res = $this->guzzle->request('POST', $url, [
                'headers' => [
                    'content-type' => 'application/json;charset=utf-8'
                ],
                'body' => $jsonBody,
            ]);

            if (!in_array(($code = $res?->getStatusCode() ?? 0), Facade\Type\AnalyticsType::ACCEPT_RESPONSE_HEADERS)) {
                Ga4Exception::throwRequestWrongResponceCode($code);
            }

            if ($code !== 204) {
                $callback = @json_decode($res->getBody()->getContents(), true);

                if (json_last_error() != JSON_ERROR_NONE) {
                    Ga4Exception::throwRequestInvalidResponse();
                } elseif (empty($callback)) {
                    Ga4Exception::throwRequestEmptyResponse();
                } elseif (!empty($callback['validationMessages'])) {
                    foreach ($callback['validationMessages'] as $msg) {
                        Ga4Exception::throwRequestInvalidBody($msg);
                    }
                }
            }
        }

        if (Ga4Exception::hasThrowStack()) {
            throw Ga4Exception::getThrowStack();
        }
    }

    public static function new(string $measurementId, string $apiSecret, bool $debug = false): static
    {
        return new static($measurementId, $apiSecret, $debug);
    }

    /**
     * Deprecated references
     */

    /** @deprecated 1.1.1 */
    public function allowPersonalisedAds(bool $allow)
    {
        $this->setNonPersonalizedAds(!$allow);
    }

    /** @deprecated 1.1.1 */
    public function setTimestamp(int|float $microOrUnix)
    {
        $this->setTimestampMicros($microOrUnix);
    }
}
