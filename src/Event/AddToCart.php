<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class AddToCart extends Model\Event implements Facade\AddToCart
{
    protected null|string $currency;
    protected null|int|float $value;
    protected array $items = [];

    public function getName(): string
    {
        return 'add_to_cart';
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

    public function setCurrency(null|string $iso)
    {
        $this->currency = $iso;
        return $this;
    }

    public function setValue(null|int|float $val)
    {
        $this->value = $val;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
