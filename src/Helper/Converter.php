<?php

namespace AlexWestergaard\PhpGa4\Helper;

class Converter
{
    /**
     * @param string $input
     * @return string snake_case
     */
    public static function snake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param string $input
     * @return string CamelCase
     */
    public static function camel(string $input): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
}
