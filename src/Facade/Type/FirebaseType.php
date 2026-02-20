<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface FirebaseType extends IOType
{
    const URL_LIVE = 'https://www.google-analytics.com/mp/collect';
    const URL_DEBUG = 'https://www.google-analytics.com/debug/mp/collect';

    const ACCEPT_RESPONSE_HEADERS = [200, 204];

    /**
     * Uniquely identifies a installation instance of an application.
     *
     * @var client_id
     * @param string $id eg. Cookie._ga or Cookie._gid
     */
    public function setAppInstanceId(string $id);

    /**
     * Mark payload as non-personalized ads.
     *
     * @var non_personalized_ads
     * @param bool $enabled
     */
    public function setNonPersonalizedAds(bool $enabled = true);

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
     * Validation behavior for debug endpoint.
     *
     * @var validation_behavior
     * @param string|null $behavior
     */
    public function setValidationBehavior(null|string $behavior);

    /**
     * Override source IP address.
     *
     * @var ip_override
     * @param string|null $ip
     */
    public function setIpOverride(null|string $ip);

    /**
     * Override user location object.
     *
     * @var user_location
     * @param array $location
     */
    public function setUserLocation(array $location);

    /**
     * Override device object.
     *
     * @var device
     * @param array $device
     */
    public function setDevice(array $device);

    /**
     * The user properties for the measurement (Up to 25 custom per project, see link)
     *
     * @var user_properties
     * @param AlexWestergaard\PhpGa4\Facade\Type\UserProperty $prop
     * @link https://support.google.com/analytics/answer/14240153
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
