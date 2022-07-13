<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class RemoveFromCart extends Model\Event implements Interface\RemoveFromCart
{
    protected $currency;
    protected $value;
    protected $items = [];

    public function getName(): string
    {
        return 'remove_to_cart';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'value',
            'items',
        ];
    }

    public function getRequiredParams(): array
    {
        $return = [];

        if (
            isset($this->currency) && !isset($this->value)
            || !isset($this->currency) && isset($this->value)
        ) {
            $return = [
                'currency',
                'value'
            ];
        }

        $return[] = 'items';
        return $return;
    }

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
    }

    public function setValue(int|float $val)
    {
        $this->value = $val;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
    }
}
