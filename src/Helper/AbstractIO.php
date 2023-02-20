<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\IO;
use AlexWestergaard\PhpGa4\Exception\Ga4IOException;

/**
 * AbstractIO will attempt to use predefined method names of 'set_' or 'add_' in camelCase naming. \
 * If you have the snake_case variable $currency_iso then a corresponding setCurrencyIso() or addCurrencyIso()
 * for magic methods such as $model['currency_iso'] to work.
 */
abstract class AbstractIO implements IO
{
    /**
     * Used to offset keyset of Iterator
     *
     * @var int
     */
    private int $currentIteratorKey = 0;

    /**
     * Used to ensure all params are in Iterator
     *
     * @var array
     */
    private array $allIteratorKeys = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->allIteratorKeys = $this->getAllParams();
    }

    /**
     * PSEUDO Check offset key with Magic Isset
     *
     * @param mixed $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * PSEUDO Remove offset key with Magic Unset
     *
     * @param mixed $name
     *
     * @return bool
     */
    public function __unset($name)
    {
        return $this->offsetUnset($name);
    }

    /**
     * PSEUDO Retrieve offset key with magic Getter
     *
     * @param mixed $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * PSEUDO Fill offset key with magic Setter
     *
     * @param mixed $name
     * @param mixed $value
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * Check if parameter exists and is gettable/settable
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        $offset = is_string($offset) ? Converter::snake($offset) : $offset;
        return in_array($offset, $this->getAllParams()) && property_exists($this, $offset);
    }

    /**
     * Retrieves the parameter if it exists or null
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        $offset = is_string($offset) ? Converter::snake($offset) : $offset;

        return $this->offsetExists($offset) ? $this->$offset : null;
    }

    /**
     * Attempts to fill parameter with given value
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $offset = is_string($offset) ? Converter::snake($offset) : $offset;

        if (!$this->offsetExists($offset)) return;

        $set = Converter::camel('set_' . $offset);
        $add = Converter::camel('add_' . $offset);
        $addSingle = Converter::camel('add_' . (substr($offset, -1) == 's' ? substr($offset, 0, -1) : $offset));

        if (method_exists($this, $set)) {
            $this->$set($value);
        } elseif (method_exists($this, $add)) {
            $this->$add($value);
        } elseif (method_exists($this, $addSingle)) {
            $this->$addSingle($value);
        }
    }

    /**
     * Attempt to unset parameter with given value
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $offset = is_string($offset) ? Converter::snake($offset) : $offset;

        if (!$this->offsetExists($offset)) return;

        $this->$offset = null;
    }

    /**
     * Attempt to return current key of Iterator key
     *
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->offsetGet($this->key());
    }

    /**
     * Attempt to return current key of Iterator key
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return $this->allIteratorKeys[$this->currentIteratorKey] ?? null;
    }

    /**
     * Moved iterator key 1 up
     *
     * @return void
     */
    public function next(): void
    {
        $this->currentIteratorKey++;
    }

    /**
     * Return the irator key to first element of array
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->currentIteratorKey = 0;
    }

    /**
     * Return the irator key to first element of array
     *
     * @return void
     */
    public function valid(): bool
    {
        $key = $this->allIteratorKeys[$this->currentIteratorKey] ?? null;
        return $key !== null && property_exists($this, $key);
    }

    /**
     * Return count of all parameters based on usable + required params \
     * presented when constructing Model
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->allIteratorKeys);
    }

    /**
     * Returns serializable set of parameters specified in getParams and getRequiredParams utilizing the toArray() method
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Returns new class of same parameters
     */
    public function __clone()
    {
        return static::fromArray($this->toArray());
    }

    /**
     * Return list of all USABLE and REQUIRED parameters
     *
     * @return array
     */
    public function getAllParams(): array
    {
        return array_unique(array_merge($this->getParams(), $this->getRequiredParams()));
    }

    /**
     * Return array of parameters specified in getParams and getRequiredParams
     *
     * @return array
     */
    public function toArray(): array
    {
        foreach ($this->getRequiredParams() as $required) {
            if (
                !in_array($required, $this->allIteratorKeys)
                || empty($this[$required]) && $this[$required] !== 0
            ) {
                throw Ga4IOException::throwMissingRequiredParam($required);
            }
        }

        $return = [];
        foreach ($this as $key => $val) {
            if (empty($val)) continue;
            $return[$key] = $val;
        }

        return $return;
    }

    /**
     * Attempt to create new model and fill parameters from array
     *
     * @param \AlexWestergaard\PhpGa4\Facade\Type\IO|array $importable
     *
     * @return static
     */
    public static function fromArray(IO|array $importable): static
    {
        $static = new static();

        $importable = $importable instanceof IO ? $importable->toArray() : $importable;

        foreach ($importable as $key => $val) {
            $static[$key] = $val;
        }

        return $static;
    }
}
