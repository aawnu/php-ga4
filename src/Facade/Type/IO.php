<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

use JsonSerializable;
use Iterator;
use Countable;
use ArrayAccess;

interface IO extends ArrayAccess, Iterator, Countable, JsonSerializable
{
    /**
     * Receive USABLE parameters of Event
     *
     * @return array<int,string>
     */
    public function getParams(): array;

    /**
     * Receive REQUIRED parameters of Event
     *
     * @return array<int,string>
     */
    public function getRequiredParams(): array;

    /**
     * Receive ALL parameters of Event
     *
     * @return array<int,string>
     */
    public function getAllParams(): array;

    /**
     * Return usable parameters as Array structure
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Attempt to fill parameters of model and return as new instance
     *
     * @param AlexWestergaard\PhpGa4\Facade\Type\IO|array $importable
     * @return static
     */
    public static function fromArray(IO|array $importable): static;
}
