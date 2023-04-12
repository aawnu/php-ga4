<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface AnalyticsType extends IOType
{
    const URL_LIVE = 'https://www.google-analytics.com/mp/collect';
    const URL_DEBUG = 'https://www.google-analytics.com/debug/mp/collect';

    const ACCEPT_RESPONSE_HEADERS = [200, 204];

    /**
     * Uniquely identifies a user instance of a web client.
     *
     * @var client_id
     * @param string $id eg. Cookie._ga or Cookie._gid
     */
    public function setClientId(string $id);

    /**
     * A unique identifier for a user. See User-ID for cross-platform analysis for more information on this identifier.
     *
     * @var user_id
     * @param string $id eg. Unique User Id
     */
    public function setUserId(string $id);

    /**
     * A Unix timestamp (in microseconds) for the time to associate with the event. This should only be set to record events that happened in the past. \
     * This value can be overridden via user_property or event timestamps. Events can be backdated up to 3 calendar days based on the property's timezone.
     *
     * @var timestamp_micros
     * @param integer|float $microOrUnix microtime(true) or time()
     */
    public function setTimestampMicros(int|float $microOrUnix);

    /**
     * Indicate if these events should be used for personalized ads.
     *
     * @var non_personalized_ads
     * @param boolean $allow
     */
    public function setNonPersonalizedAds(bool $allow);

    /**
     * The user properties for the measurement
     *
     * @var user_properties
     * @param AlexWestergaard\PhpGa4\Facade\Type\UserProperty $prop
     */
    public function addUserProperty(UserPropertyType ...$props);

    /**
     * An array of event items. Up to 25 events can be sent per request
     *
     * @var events
     * @param AlexWestergaard\PhpGa4\Facade\Type\Event $event
     */
    public function addEvent(EventType ...$events);

    /**
     * Validate params and send it to Google Analytics
     *
     * @return void
     * @throws AlexWestergaard\PhpGa4\Exception\Ga4Exception
     */
    public function post();
}
