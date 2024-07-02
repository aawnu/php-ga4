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

    private Helper\ConsentHelper $consent;
    private Helper\UserDataHelper $userdata;

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
        $this->consent = new Helper\ConsentHelper();
        $this->userdata = new Helper\UserDataHelper();
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

    public function consent(): Helper\ConsentHelper
    {
        return $this->consent;
    }

    public function userdata(): Helper\UserDataHelper
    {
        return $this->userdata;
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

        $body = array_replace_recursive(
            $this->toArray(),
            ["user_data" => $this->user_id != null ? $this->userdata->toArray() : []], // Only accepted if user_id is passed too
            ["user_properties" => $this->user_properties],
            ["consent" => $this->consent->toArray()],
        );

        if (count($body["user_data"]) < 1) unset($body["user_data"]);
        if (count($body["user_properties"]) < 1) unset($body["user_properties"]);

        $chunkEvents = array_chunk($this->events, 25);

        if (count($chunkEvents) < 1) {
            throw Ga4Exception::throwMissingEvents();
        }

        $this->userdata->reset();
        $this->user_properties = [];
        $this->events = [];

        foreach ($chunkEvents as $events) {
            $body['events'] = $events;

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

    /** @deprecated 1.1.9 Please use `Analytics->consent->setAdPersonalizationPermission()` instead */
    public function setNonPersonalizedAds(bool $exclude)
    {
        $this->consent->setAdPersonalizationPermission(!$exclude);
        return $this;
    }

    /** @deprecated 1.1.1 Please use `Analytics->consent->setAdPersonalizationPermission()` instead */
    public function allowPersonalisedAds(bool $allow)
    {
        $this->consent->setAdPersonalizationPermission($allow);
    }

    /** @deprecated 1.1.1 Please use `Analytics->setTimestampMicros()` instead */
    public function setTimestamp(int|float $microOrUnix)
    {
        $this->setTimestampMicros($microOrUnix);
    }
}
