<?php

namespace AlexWestergaard\PhpGa4\Exception;

class Ga4IOException extends Ga4Exception
{
    public static function throwMissingRequiredParam($param)
    {
        return new static("Missing required parameter: $param", static::PARAM_MISSING_REQUIRED);
    }
}
