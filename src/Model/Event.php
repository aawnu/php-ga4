<?php

namespace AlexWestergaard\PhpGa4\Model;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Interface;

abstract class Event extends ToArray implements Interface\Export
{
    /**
     * Return the name of the event
     *
     * @return string
     */
    abstract public function getName(): string;

    abstract public function getParams(): array;

    abstract public function getRequiredParams(): array;

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        $return = [];
        $errorStack = null;

        if (!method_exists($this, 'getName')) {
            $errorStack = new GA4Exception("'self::getName()' does not exist", 0, $errorStack);
        } else {
            $name = $this->getName();
            if (empty($name)) {
                $errorStack = new GA4Exception("'self::getName()' can not be empty", 0, $errorStack);
            } else {
                $return['name'] = $name;
            }
        }

        $catch = parent::toArray(true, $errorStack);
        $errorStack = $catch['error'];

        if (is_array($catch['data']) && !empty($catch['data'])) {
            $return['params'] = $catch['data'];
        }

        if ($errorStack instanceof GA4Exception) {
            throw $errorStack;
        }

        return $return;
    }
}
