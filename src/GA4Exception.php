<?php

namespace AlexWestergaard\PhpGa4;

class GA4Exception extends \Exception
{
    private static ?GA4Exception $GA4ExceptionStack = null;

    public function __construct(string $message = "", int $code = 0)
    {
        $previous = isset(static::$GA4ExceptionStack) ? static::$GA4ExceptionStack : null;

        parent::__construct($message, $code, $previous);
    }

    public function add()
    {
        self::$GA4ExceptionStack = $this;
    }

    public static function push(string $message, int $code = 0)
    {
        self::$GA4ExceptionStack = new static($message, $code);
    }

    public static function hasStack()
    {
        return isset(self::$GA4ExceptionStack) && self::$GA4ExceptionStack !== null;
    }

    public static function getStack()
    {
        return self::$GA4ExceptionStack;
    }

    public static function resetStack()
    {
        static::$GA4ExceptionStack = null;
    }
}
