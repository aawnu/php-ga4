<?php

namespace AlexWestergaard\PhpGa4\Trait;

trait StaticNew
{
    /** Please refer to __construct args */
    public static function new(...$args): static
    {
        return new static(...$args);
    }
}
