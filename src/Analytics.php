<?php

namespace AlexWestergaard\PhpGa4;

use GuzzleHttp\Client as Guzzle;
use AlexWestergaard\PhpGa4\Helper\AbstractIO;
use AlexWestergaard\PhpGa4\Facade\Type\UserProperty;
use AlexWestergaard\PhpGa4\Facade\Type\Event;
use AlexWestergaard\PhpGa4\Facade\Type\Analytics as TypeAnalytics;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;

class Analytics extends AbstractIO implements TypeAnalytics
{
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

    public function setNonPersonalizedAds(bool $allow)
    {
        $this->non_personalized_ads = !$allow;
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
        $secondInMicro = 1_000_000;
        $offsetLimit = (strtotime('-3 days') + 90) * $secondInMicro;

        if (!is_numeric($microOrUnix)) {
            throw Ga4Exception::throwMicrotimeInvalidFormat();
        }

        $formattedTime =  floor($microOrUnix * $secondInMicro);
        if ($formattedTime < $offsetLimit) {
            throw Ga4Exception::throwMicrotimeExpired();
        }

        $this->timestamp_micros = intval($formattedTime);
        return $this;
    }

    public function addUserProperty(UserProperty $prop)
    {
        $this->user_properties = array_replace($this->user_properties, $prop->toArray());
        return $this;
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event->toArray();
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

        $url = $this->debug ? TypeAnalytics::URL_DEBUG : TypeAnalytics::URL_LIVE;
        $url .= '?' . http_build_query(['measurement_id' => $this->measurement_id, 'api_secret' => $this->api_secret]);

        $body = $this->toArray();

        $chunkUserProperties = array_chunk($this->user_properties, 25, true);
        $this->user_properties = [];

        $chunkEvents = array_chunk($this->events, 25);
        $this->events = [];

        $chunkMax = count($chunkEvents) > count($chunkUserProperties) ? count($chunkEvents) : count($chunkUserProperties);

        for ($chunk = 0; $chunk < $chunkMax; $chunk++) {
            $body['user_properties'] = $chunkUserProperties[$chunk] ?? [];
            $body['events'] = $chunkEvents[$chunk] ?? [];

            $kB = 1024;
            if (($size = mb_strlen(json_encode($body))) > ($kB * 130)) {
                Ga4Exception::throwRequestTooLarge(intval($size / $kB));
                continue;
            }

            $guzzle = new Guzzle();
            $res = $guzzle->request('POST', $url, ['json' => $body]);

            if (!in_array(($code = $res?->getStatusCode() ?? 0), TypeAnalytics::ACCEPT_RESPONSE_HEADERS)) {
                Ga4Exception::throwRequestWrongResponceCode($code);
            }

            if ($code !== 204) {
                $callback = @json_decode($res->getBody()->getContents(), true);

                if (empty($callback)) {
                    Ga4Exception::throwRequestEmptyResponse();
                } elseif (json_last_error() != JSON_ERROR_NONE) {
                    Ga4Exception::throwRequestInvalidResponse();
                } elseif (!empty($callback['validationMessages'])) {
                    foreach ($callback['validationMessages'] as $msg) {
                        Ga4Exception::throwRequestInvalidBody($msg);
                    }
                }
            }

            if (Ga4Exception::hasThrowStack()) {
                throw Ga4Exception::getThrowStack();
            }
        }
    }

    public static function new(string $measurementId, string $apiSecret, bool $debug = false): static
    {
        return new static($measurementId, $apiSecret, $debug);
    }
}
