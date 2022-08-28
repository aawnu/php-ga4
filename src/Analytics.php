<?php

namespace AlexWestergaard\PhpGa4;

use GuzzleHttp\Client as Guzzle;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

/**
 * Foundation class to collect all information and events to send to Google Analytics \
 * Make sure to get you Measurement ID and a API Secret
 * @link https://analytics.google.com/ -> Settings -> Data stream -> API Secrets & Measurement Protocol -> Create
 */
class Analytics extends Model\ToArray implements Facade\Analytics, Facade\Export
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
    protected $user_properties = [];
    protected $events = [];

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

    public function allowPersonalisedAds(bool $allow)
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

    /**
     * @param int|float $microOrUnix time() or microtime(true)
     */
    public function setTimestamp($microOrUnix)
    {
        $secondInMicro = intval(strtr('1_000_000', ['_' => '']));
        $offsetLimit = strtotime('-3 days') * $secondInMicro;

        if (!is_numeric($microOrUnix)) {
            throw new GA4Exception("setTimestamp value must be numeric");
        }

        $formattedTime =  floor($microOrUnix * $secondInMicro);
        if ($formattedTime < $offsetLimit) {
            throw new GA4Exception("Timestamp can not be older than 3 days");
        }

        $this->timestamp_micros = $formattedTime;
        return $this;
    }

    /**
     * Add user property to your analytics request \
     * Maximum is 25 per request
     *
     * @param AlexWestergaard\PhpGa4\UserProperty $event
     * @return int How many events you have added
     * @throws AlexWestergaard\PhpGa4\GA4Exception
     */
    public function addUserProperty(UserProperty $prop)
    {
        if (count($this->user_properties) >= 25) {
            throw new GA4Exception("Can't add more than 25 user properties");
        }

        $catch = $prop->toArray();

        $this->user_properties[$catch['name']] = $catch['value'];
        return $this;
    }

    /**
     * Add event to your analytics request \
     * Maximum is 25 per request
     *
     * @param AlexWestergaard\PhpGa4\Model\Event $event
     * @return int How many events you have added
     * @throws AlexWestergaard\PhpGa4\GA4Exception
     */
    public function addEvent(Model\Event $event)
    {
        $this->events[] = $event->toArray();

        return $this;
    }

    /**
     * Push your current stack to Google Analytics \
     * Will reset the events list on success
     *
     * @return bool Whether the request returned status 200
     * @throws AlexWestergaard\PhpGa4\GA4Exception
     */
    public function post()
    {
        $url = $this->debug ? $this::URL_DEBUG : $this::URL_LIVE;
        $url .= '?' . http_build_query(['measurement_id' => $this->measurement_id, 'api_secret' => $this->api_secret]);

        $reqBody = parent::toArray(true);

        $eventsList = array_chunk($reqBody['events'] ?? [], 25);

        foreach ($eventsList as $events) {
            $reqBody['events'] = $events;

            $kB = 1024;
            if (mb_strlen(json_encode($reqBody)) > ($kB * 130)) {
                GA4Exception::push("Request body exceeds 130kB");
            }

            $guzzle = new Guzzle();
            $res = $guzzle->request('POST', $url, ['json' => $reqBody]);

            $resCode = $res->getStatusCode() ?? 0;
            if ($resCode !== 200) {
                GA4Exception::push("Request received code {$resCode}");
            }

            $resBody = $res->getBody()->getContents();
            $data = @json_decode($resBody, true);

            if (empty($resBody)) {
                GA4Exception::push("Received not body");
            } elseif (json_last_error() != JSON_ERROR_NONE || $data === null) {
                GA4Exception::push("Could not parse response");
            } elseif (!empty($data['validationMessages'])) {
                foreach ($data['validationMessages'] as $msg) {
                    GA4Exception::push(
                        'Validation Message: ' . $msg['validationCode']
                            . (isset($msg['fieldPath']) ? '[' . $msg['fieldPath'] . ']: ' : ':')
                            . $msg['description']
                    );
                }
            }
        }

        if (GA4Exception::hasStack()) {
            throw GA4Exception::getStack();
        }

        $this->events = [];
        GA4Exception::resetStack();

        return true;
    }

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        return parent::toArray($isParent, $childErrors);
    }

    public static function new(string $measurementId, string $apiSecret, bool $debug = false)
    {
        return new static($measurementId, $apiSecret, $debug);
    }
}
