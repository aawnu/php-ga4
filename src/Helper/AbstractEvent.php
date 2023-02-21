<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\Event;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;

abstract class AbstractEvent extends AbstractIO implements Event
{
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
            } elseif (preg_match('/[^\w\d\-]/', $name)) {
                throw Ga4EventException::throwNameInvalid();
            } elseif (in_array($name, Event::RESERVED_NAMES)) {
                throw Ga4EventException::throwNameReserved($name);
            } else {
                $return['name'] = $name;
            }
        }

        $return['params'] = parent::toArray();

        return $return;
    }
    
    public static function new(): static
    {
        return new static();
    }
}
