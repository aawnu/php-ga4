<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class BeginCheckout extends Model\Event implements Interface\BeginCheckout
{
    protected $currency;
    protected $value;
    protected $coupon;
    protected $items = [];

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

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
    }

    public function setValue(int|float $val)
    {
        $this->value = $val;
    }

    public function setCoupon(string $code)
    {
        $this->coupon = $code;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
    }
}
