<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class BeginCheckout extends Model\Event implements Facade\BeginCheckout
{
    protected null|string $currency;
    protected null|int|float $value;
    protected null|string $coupon;
    protected array $items = [];

    public function getName(): string
    {
        return 'begin_checkout';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'value',
            'coupon',
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

    public function setCoupon(null|string $code)
    {
        $this->coupon = 0 + $code;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }

    public function resetItems()
    {
        $this->items = [];
    }
}
