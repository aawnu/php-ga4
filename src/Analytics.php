<?php

namespace AlexWestergaard\PhpGa4;

use GuzzleHttp\Client as Guzzle;
use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class Analytics extends Model\ToArray implements Interface\Analytics, Interface\Export
{
    const URL_LIVE = 'https://www.google-analytics.com/mp/collect';
    const URL_DEBUG = 'https://www.google-analytics.com/debug/mp/collect';

    private $debug;
    private $measurement_id;
    private $api_secret;

    protected $non_personalized_ads;
    protected $timestamp_micros;
    protected $client_id;
    protected $user_id;
    protected $user_properties;
    protected $events;

    public function __construct(string $measurementId, string $apiSecret, bool $debug = false)
    {
        $this->measurement_id = $measurementId;
        $this->api_secret = $apiSecret;
        $this->debug = $debug;
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
        return [
            'client_id',
            'events'
        ];
    }

    public function allowPersonalisedAds(bool $allow)
    {
        $this->non_personalized_ads = !$allow;
    }

    public function setClientId(string $id)
    {
        $this->client_id = $id;
    }

    public function setUserId(string $id)
    {
        $this->user_id = $id;
    }

    public function setTimestamp(int|float $microOrUnix)
    {
        $this->timestamp_micros = floor($microOrUnix * 1000);
    }

    public function addUserProperty(UserProperty $prop)
    {
        $this->user_properties[] = $prop->toArray();
    }

    public function addEvent(Model\Event $event)
    {
        $this->events[] = $event->toArray();
    }

    public function post(bool $validate = false)
    {
        $errorStack = null;

        $url = $this->debug || $validate ? $this::URL_DEBUG : $this::URL_LIVE;
        $url .= '?' . http_build_query(['measurement_id' => $this->measurement_id, 'api_secret' => $this->api_secret]);

        $catch = parent::toArray(true, $errorStack);
        $errorStack = $catch['error'];
        $body = $catch['data'];

        $guzzle = new Guzzle();
        $res = $guzzle->request('POST', $url, ['json' => $body]);

        $code = $res->getStatusCode() ?? 0;
        if ($code !== 200) {
            $errorStack = new GA4Exception("REquest received code {$code}", $errorStack);
        }

        if ($errorStack instanceof GA4Exception) {
            throw $errorStack;
        }

        $body = $res->getBody()->getContents();
        $data = @json_decode($body, true);

        if (empty($body)) {
            $errorStack = new GA4Exception("Received not body", $errorStack);
        } elseif (json_last_error() != JSON_ERROR_NONE || $data === null) {
            $errorStack = new GA4Exception("Could not parse response", $errorStack);
        } elseif (!empty($data['validationMessages'])) {
            foreach ($data['validationMessages'] as $msg) {
                $errorStack = new GA4Exception('Validation Message: ' . $msg['validationCode'] . '[' . $msg['fieldPath'] . ']: ' . $msg['description'], $errorStack);
            }
        }

        if ($validate) {
            echo "Request \\ ", $url, "\r\n", json_encode($catch['data'], JSON_PRETTY_PRINT), "\r\n\r\n";
            echo "Response \\ ", $code, "\r\n", json_encode($data, JSON_PRETTY_PRINT), "\r\n\r\n";
        }

        if ($errorStack instanceof GA4Exception) {
            throw $errorStack;
        }

        return $code === 200;
    }

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        return parent::toArray($isParent, $childErrors);
    }
}
