<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\GtmEventType;
use AlexWestergaard\PhpGa4\Facade\Type\EventType;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;

abstract class EventMainHelper extends IOHelper implements EventType
{
    protected null|string $session_id;
    protected null|int $engagement_time_msec;

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

        $return['params'] = parent::toArray();

        return $return;
    }

    public function getAllParams(): array
    {
        return array_unique(array_merge(
            $this->getParams(),
            $this->getRequiredParams()
        ));
    }

    public static function new(): static
    {
        return new static();
    }
}
