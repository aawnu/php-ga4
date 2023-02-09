<?php

namespace AlexWestergaard\PhpGa4\Model;

use ArrayAccess;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Helper\Helper;

abstract class Event extends ToArray implements Facade\Export, ArrayAccess
{
    private $debug = false;

    /**
     * Return the name of the event
     *
     * @return string
     */
    abstract public function getName(): string;

    public function debug($on = true)
    {
        $this->debug = boolval($on);

        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, Helper::snake($offset));
    }

    public function offsetGet(mixed $offset): mixed
    {
        $callable = Helper::snake($offset);
        return $this->offsetExists($callable) ? $this->$callable : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $callable = Helper::camel($offset);
        if ($this->offsetExists($offset)) {
            $this->$callable = $value;
            if (method_exists($this, ($method = 'add' . $callable))) {
                $this->$method($value);
            } elseif (method_exists($this, ($method = 'set' . $callable))) {
                $this->$method($value);
            }

            if (is_array($value)) {
                $callable = substr($callable, -1) === 's' ? substr($callable, 0, -1) : $callable;

                foreach ($value as $paramRow) {
                    if (method_exists($this, ($method = 'add' . $callable))) {
                        $this->$method($paramRow);
                    } elseif (method_exists($this, ($method = 'set' . $callable))) {
                        $this->$method($paramRow);
                    }
                }
            } else {
                if (method_exists($this, ($method = 'add' . $callable))) {
                    $this->$method($value);
                } elseif (method_exists($this, ($method = 'set' . $callable))) {
                    $this->$method($value);
                }
            }
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        $var = Helper::snake($offset);
        if ($this->offsetExists($offset)) {
            $this->$var = null;
        }
    }

    /**
     * @param GA4Exception $childErrors
     */
    public function toArray(bool $isParent = false): array
    {
        $return = [];

        if (!method_exists($this, 'getName')) {
            GA4Exception::push("'getName()' does not exist");
        } else {
            $name = $this->getName();
            if (empty($name)) {
                GA4Exception::push("Name '{$name}' can not be empty");
            } elseif (strlen($name) > 40) {
                GA4Exception::push("Name '{$name}' can not be longer than 40 characters");
            } elseif (preg_match('/[^\w\d\-]/', $name)) {
                GA4Exception::push("Name '{$name}' can only be letters, numbers, underscores and dashes");
            } elseif (in_array($name, Helper::RESERVED_EVENT_NAMES)) {
                GA4Exception::push("Name '{$name}' is reserved");
            } else {
                $return['name'] = $name;
            }
        }

        $parent = parent::toArray(true);

        if ($this->debug) {
            $parent['debug_mode'] = true;
        }

        if (!$isParent && GA4Exception::hasStack()) {
            throw GA4Exception::getFinalStack();
        }

        if (!empty($parent)) {
            $return['params'] = $parent;
        }

        return $return;
    }

    public static function fromArray(array $params = [])
    {
        $event = static::new();

        $insertables = array_unique(array_merge($event->getParams(), $event->getRequiredParams()));

        foreach ($insertables as $insertable) {
            if (!in_array($insertable, array_keys($params)) || is_null($param = $params[$insertable])) {
                continue;
            }

            $event[$insertable] = $param;
        }

        return $event;
    }

    public static function new()
    {
        return new static();
    }
}
