<?php

namespace AlexWestergaard\PhpGa4;

class GA4Exception extends \Exception
{
    /**
     * Contains ongoing stack of errors that can be returned as chained exceptions
     *
     * @var AlexWestergaard\PhpGa4\GA4Exception
     */
    private static $GA4ExceptionStack;

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, static::getStack());
    }

    /**
     * Add current exception to stack
     *
     * @return static
     */
    public function add()
    {
        self::$GA4ExceptionStack = $this;
        return $this;
    }

    /**
     * Clean stack - will not affect current instance
     *
     * @return static
     */
    public function clean()
    {
        static::resetStack();
        return $this;
    }

    /**
     * Add new GA4Exception to stack without further action
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public static function push(string $message, int $code = 0)
    {
        self::$GA4ExceptionStack = new static($message, $code);
    }

    /**
     * Check if the stack has any instances
     *
     * @return bool
     */
    public static function hasStack()
    {
        return static::getStack() !== null;
    }

    /**
     * Return unmodified stack
     *
     * @return AlexWestergaard\PhpGa4\GA4Exception|null
     */
    public static function getStack()
    {
        return isset(self::$GA4ExceptionStack) && self::$GA4ExceptionStack instanceof GA4Exception ? self::$GA4ExceptionStack : null;
    }

    /**
     * Returns stack and resets/cleans the stack; this one should be used when throwing \
     * to avoid future try-catch blocks will hit the stack build during current run.
     *
     * @return AlexWestergaard\PhpGa4\GA4Exception|null
     */
    public static function getFinalStack()
    {
        $stack = static::getStack();

        static::resetStack();

        return $stack;
    }

    /**
     * Resets/cleans the stack
     *
     * @return void
     */
    public static function resetStack()
    {
        self::$GA4ExceptionStack = null;
    }
}
