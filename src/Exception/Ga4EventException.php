<?php

namespace AlexWestergaard\PhpGa4\Exception;

class Ga4EventException extends GA4IOException
{
    public static function throwNameReserved($name)
    {
        return new static("Name is reserved: $name", static::EVENT_NAME_RESERVED);
    }

    public static function throwNameMissing()
    {
        return new static("Name is missing or empty", static::EVENT_NAME_MISSING);
    }

    public static function throwNameTooLong()
    {
        return new static("Name is too long, max 40", static::EVENT_NAME_TOO_LONG);
    }

    public static function throwNameInvalid()
    {
        return new static("Name can only be letters, numbers, underscores and dashes", static::EVENT_NAME_INVALID);
    }
}
