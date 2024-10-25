<?php

namespace AlexWestergaard\PhpGa4\Model;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Facade;

abstract class ToArray implements Facade\Export
{
    abstract public function getParams(): array;

    abstract public function getRequiredParams(): array;

    /**
     * @param GA4Exception $childErrors
     */
    public function toArray(bool $isParent = false): array
    {
        $return = [];

        $required = $this->getRequiredParams();
        $params = array_unique(array_merge($this->getParams(), $required));

        foreach ($params as $param) {
            if (!property_exists($this, $param)) {
                GA4Exception::push("Param '{$param}' is not defined");
                continue;
            } elseif (!isset($this->{$param})) {
                if (in_array($param, $required)) {
                    GA4Exception::push("Param '{$param}' is required but not set");
                }
                continue;
            } elseif (empty($this->{$param}) && (is_array($this->{$param}) || strval($this->{$param}) !== '0')) {
                if (in_array($param, $required)) {
                    GA4Exception::push("Param '{$param}' is required but empty");
                }
                continue;
            }

            if (strlen($param) > 40) {
                GA4Exception::push("Param '{$param}' is too long, maximum is 40 characters");
            }

            $value = $this->{$param};

            // Array values be handled and validated within setter, fx addItem/setItem
            if (!is_array($value) && mb_strlen($value) > 100) {
                GA4Exception::push("Value '{$value}' is too long, maximum is 100 characters");
            }

            $return[$param] = $value;
        }

        if (!$isParent && GA4Exception::hasStack()) {
            throw GA4Exception::getFinalStack();
        }

        return $return;
    }
}
