<?php

namespace AlexWestergaard\PhpGa4;

class GA4Exception extends \Exception
{
    private static GA4Exception $GA4ExceptionStack;

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, static::getStack());
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
        return static::getStack() !== null;
    }

    public static function getStack()
    {
        return isset(static::$GA4ExceptionStack) ? static::$GA4ExceptionStack : null;
    }

    public static function getFinalStack()
    {
        $stack = isset(static::$GA4ExceptionStack) ? static::$GA4ExceptionStack : null;

        static::resetStack();

        return $stack;
    }

    public static function resetStack()
    {
        static::$GA4ExceptionStack = null;
    }
}
