<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\GtmEventType;
use AlexWestergaard\PhpGa4\Facade\Type\EventType;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;

abstract class EventMainHelper extends IOHelper implements EventType
{
    protected null|string $session_id;
    protected null|int $engagement_time_msec;
    protected null|int $event_timestamp_micros;

    public function setSessionId(string $id)
    {
        $this->session_id = $id;
        return $this;
    }

    public function setEngagementTimeMSec(int $msec)
    {
        $this->engagement_time_msec = $msec;
        return $this;
    }

    public function setEventTimestampMicros(int|float $microOrUnix)
    {
        $min = ConvertHelper::timeAsMicro(strtotime('-3 days') + 10);
        $max = ConvertHelper::timeAsMicro(time() + 3);

        $time = ConvertHelper::timeAsMicro($microOrUnix);

        if ($time < $min || $time > $max) {
            throw Ga4Exception::throwMicrotimeExpired();
        }

        $this->event_timestamp_micros = $time;
        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        $offset = is_string($offset) ? ConvertHelper::snake($offset) : $offset;
        return $offset === 'timestamp_micros' || parent::offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $offset = is_string($offset) ? ConvertHelper::snake($offset) : $offset;
        if ($offset === 'timestamp_micros') {
            return $this->event_timestamp_micros;
        }

        return parent::offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $offset = is_string($offset) ? ConvertHelper::snake($offset) : $offset;
        if ($offset === 'timestamp_micros') {
            if ($value === null) {
                $this->event_timestamp_micros = null;
            } else {
                $this->setEventTimestampMicros($value);
            }
            return;
        }

        parent::offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $offset = is_string($offset) ? ConvertHelper::snake($offset) : $offset;
        if ($offset === 'timestamp_micros') {
            $this->event_timestamp_micros = null;
            return;
        }

        parent::offsetUnset($offset);
    }

    public function toArray(): array
    {
        $return = [];

        if (!method_exists($this, 'getName')) {
            throw Ga4EventException::throwNameMissing();
        } else {
            $name = $this->getName();

            if (empty($name)) {
                throw Ga4EventException::throwNameMissing();
            } elseif (strlen($name) > 40) {
                throw Ga4EventException::throwNameTooLong();
            } elseif (preg_match('/[^\w\d\-]|^\-|\-$/', $name)) {
                throw Ga4EventException::throwNameInvalid();
            } elseif (in_array($name, EventType::RESERVED_NAMES) && !($this instanceof GtmEventType)) {
                throw Ga4EventException::throwNameReserved($name);
            } else {
                $return['name'] = $name;
            }
        }

        if ($this->event_timestamp_micros !== null) {
            $return['timestamp_micros'] = $this->event_timestamp_micros;
        }

        $return['params'] = parent::toArray();

        return $return;
    }

    public function getAllParams(): array
    {
        return array_unique(array_merge(
            [
                'session_id',
                'engagement_time_msec',
            ],
            $this->getParams(),
            $this->getRequiredParams()
        ));
    }

    public static function new(): static
    {
        return new static();
    }
}
