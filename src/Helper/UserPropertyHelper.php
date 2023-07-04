<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\UserPropertyType;
use AlexWestergaard\PhpGa4\Exception\Ga4UserPropertyException;

abstract class UserPropertyHelper extends IOHelper implements UserPropertyType
{
    public function setName(string $name): static
    {
        if (
            in_array($name, UserPropertyType::RESERVED_NAMES)
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

        if (!isset($this->name)) {
            throw Ga4UserPropertyException::throwNameMissing();
        }

        $value = isset($this->value) ? $this->value : null;
        if (!is_array($value)) {
            $value = ['value' => $value];
        }

        $return[$this->name] = $value;

        return $return;
    }

    public static function new(): static
    {
        return new static();
    }

    public static function make(string $name, $value): static
    {
        return static::new()
            ->setName($name)
            ->setValue($value);
    }
}
