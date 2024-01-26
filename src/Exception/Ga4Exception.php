<?php

namespace AlexWestergaard\PhpGa4\Exception;

use AlexWestergaard\PhpGa4\Facade\Type\Ga4ExceptionType;

class Ga4Exception extends \Exception implements Ga4ExceptionType
{
    private static ?Ga4Exception $exceptionStack = null;

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, static::$exceptionStack);
        static::$exceptionStack = $this;
    }

    public static function hasThrowStack(): bool
    {
        return static::$exceptionStack !== null;
    }

    public static function getThrowStack(): ?Ga4Exception
    {
        $stack = static::$exceptionStack;
        static::resetStack();

        return $stack;
    }

    public static function resetStack(): void
    {
        static::$exceptionStack = null;
    }

    public static function throwMissingMeasurementId()
    {
        return new static("Missing Measurement ID", static::REQUEST_MISSING_MEASUREMENT_ID);
    }

    public static function throwMissingApiSecret()
    {
        return new static("Missing API Secret", static::REQUEST_MISSING_API_SECRET);
    }

    public static function throwMicrotimeInvalid($inp)
    {
        return new static("Timestamp $inp is not valid", static::MICROTIME_INVALID_FORMAT);
    }

    public static function throwMicrotimeExpired()
    {
        return new static("Timestamp is too old, max 3 days from NOW", static::MICROTIME_EXPIRED);
    }

    public static function throwRequestTooLarge(int $kb)
    {
        return new static("Request body ({$kb}kB) exceeds maximum of 130kB", static::REQUEST_TOO_LARGE);
    }

    public static function throwRequestWrongResponceCode(int $code)
    {
        return new static("Request returned with invalid response code $code", static::REQUEST_WRONG_RESPONSE_CODE);
    }

    public static function throwRequestEmptyResponse()
    {
        return new static("Request returned empty body", static::REQUEST_EMPTY_RESPONSE);
    }

    public static function throwRequestInvalidResponse()
    {
        return new static("Request return invalid body format; expected json", static::REQUEST_INVALID_RESPONSE);
    }

    public static function throwRequestInvalidBody(array $msg)
    {
        return new static(
            'Validation Message > ' . $msg['validationCode']
                . (isset($msg['fieldPath']) ? ' [' . $msg['fieldPath'] . ']: ' : ': ')
                . $msg['description'],
            static::REQUEST_INVALID_BODY
        );
    }
}
