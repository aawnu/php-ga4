<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Trait\StaticNew;
use AlexWestergaard\PhpGa4\Facade\Type\UserProperty;
use AlexWestergaard\PhpGa4\Exception\Ga4UserPropertyException;

abstract class AbstractUserProperty extends AbstractIO implements UserProperty
{
    use StaticNew;

    public function setName(string $name): static
    {
        if (
            in_array($name, UserProperty::RESERVED_NAMES)
            || substr($name, 0, 9) == 'firebase_'
            || substr($name, 0, 7) == 'google_'
            || substr($name, 0, 4) == 'ga_'
        ) {
            throw Ga4UserPropertyException::throwNameReserved($name);
        } elseif (mb_strlen($name) > 24) {
            throw Ga4UserPropertyException::throwNameTooLong($name);
        }

        $this->name = $name;
        return $this;
    }

    public function toArray(): array
    {
        $return = [];

        $return[$this->name] = $this->value;

        if (!is_array($this->value)) {
            $return[$this->name] = ['value' => $this->value];
        }

        return $return;
    }
}
