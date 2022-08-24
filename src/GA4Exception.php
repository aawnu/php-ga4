<?php

namespace AlexWestergaard\PhpGa4;

class GA4Exception extends \Exception
{
    public function __construct(string $message = "", $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
