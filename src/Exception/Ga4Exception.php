<?php

namespace AlexWestergaard\PhpGa4\Exception;

use AlexWestergaard\PhpGa4\Facade\Type\Ga4Exception as TypeGa4Exception;

class Ga4Exception extends \Exception implements TypeGa4Exception
{
    private static $exceptionStack = [];

    public function toStack()
    {
        static::$exceptionStack[] = $this;
        return $this;
    }

    public static function addStack(TypeGa4Exception $ga4Exception): void
    {
        static::$exceptionStack[] = $ga4Exception;
    }

    public static function getStack(bool $reset = false): array
    {
        $stack = static::$exceptionStack;

        if ($reset) {
            static::resetStack();
        }

        return $stack;
    }

    public static function resetStack(): void
    {
        static::$exceptionStack = [];
    }
}
