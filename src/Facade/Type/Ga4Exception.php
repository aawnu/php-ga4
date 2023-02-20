<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface Ga4Exception
{
    const PARAM_MISSING_REQUIRED = 1000;
    
    const PARAM_RESERVED = 2000;
    const PARAM_TOO_LONG = 2001;

    const EVENT_NAME_RESERVED = 3000;
    const EVENT_NAME_MISSING = 3001;
    const EVENT_NAME_TOO_LONG = 3002;
    const EVENT_NAME_INVALID = 3003;
}
