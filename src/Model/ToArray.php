<?php

namespace AlexWestergaard\PhpGa4\Model;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Interface;

abstract class ToArray implements Interface\Export
{
    abstract public function getParams(): array;

    abstract public function getRequiredParams(): array;

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        $return = [];
        $errorStack = null;

        if ($isParent !== null) {
            $errorStack = $childErrors;
        }

        $required = $this->getRequiredParams();
        $params = array_unique(array_merge($this->getParams(), $required));

        foreach ($params as $param) {
            if (!property_exists($this, $param)) {
                $errorStack = new GA4Exception("'{$param}' is not defined", 0, $errorStack);
                continue;
            } elseif (!isset($this->{$param})) {
                if (in_array($param, $required)) {
                    $errorStack = new GA4Exception("'{$param}' is required but not set", 0, $errorStack);
                }
                continue;
            } elseif (empty($this->{$param}) && (is_array($this->{$param}) || strval($this->{$param}) !== 0)) {
                if (in_array($param, $required)) {
                    $errorStack = new GA4Exception("'{$param}' is required but empty", 0, $errorStack);
                }
                continue;
            }

            $return[$param] = $this->{$param};
        }

        if ($isParent) {
            return [
                'data' => $return,
                'error' => $childErrors
            ];
        } elseif ($errorStack instanceof GA4Exception) {
            throw $errorStack;
        }

        return $return;
    }
}
