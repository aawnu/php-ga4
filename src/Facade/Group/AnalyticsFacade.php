<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

use AlexWestergaard\PhpGa4\Facade\Type;

interface AnalyticsFacade
{
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
     * The user properties for the measurement
     *
     * @var user_properties
     * @param AlexWestergaard\PHPGA4Module\UserProperty $prop
     */
    public function addUserProperty(Type\UserPropertyType $prop);

    /**
     * An array of event items. Up to 25 events can be sent per request
     *
     * @var events
     * @param AlexWestergaard\PHPGA4Module\Event $event
     */
    public function addEvent(Type\EventType $event);

    /**
     * Validate params and send it to Google Analytics
     *
     * @return bool
     */
    public function post();
}
