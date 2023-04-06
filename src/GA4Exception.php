<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Exception\Ga4Exception as ExceptionGa4Exception;

/** @deprecated 1.1.1 */
class GA4Exception extends \Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable|null $previous = null)
    {
        $new = new ExceptionGa4Exception($message, $code);
        parent::__construct("This exception is deprecated, check previous for your exception", 0, $new);
    }
}
