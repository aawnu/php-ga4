<?php

namespace AlexWestergaard\PhpGa4\Exception;

class Ga4UserPropertyException extends GA4IOException
{
    public static function throwNameReserved($name)
    {
        return new static("Name is reserved: $name", static::PARAM_RESERVED);
    }

    public static function throwNameTooLong($name)
    {
        return new static("Name is too long, max is 24: $name", static::PARAM_TOO_LONG);
    }
}
