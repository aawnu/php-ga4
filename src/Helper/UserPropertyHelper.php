<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\UserPropertyType;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Exception\Ga4UserPropertyException;

abstract class UserPropertyHelper extends IOHelper implements UserPropertyType
{
    protected null|int $set_timestamp_micros;

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

    public function setTimestampMicros(int|float $microOrUnix): static
    {
        $min = ConvertHelper::timeAsMicro(strtotime('-3 days') + 10);
        $max = ConvertHelper::timeAsMicro(time() + 3);

        $time = ConvertHelper::timeAsMicro($microOrUnix);

        if ($time < $min || $time > $max) {
            throw Ga4Exception::throwMicrotimeExpired();
        }

        $this->set_timestamp_micros = $time;
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

        if (isset($this->set_timestamp_micros)) {
            $value['set_timestamp_micros'] = $this->set_timestamp_micros;
        }

        $return[$this->name] = $value;

        return $return;
    }

    public static function new(): static
    {
        return new static();
    }
}
