<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class AddShippingInfo extends Model\Event implements Interface\AddShippingInfo
{
    protected $currency;
    protected $value;
    protected $coupon;
    protected $shipping_tier;
    protected $items = [];

    public function getName(): string
    {
        return 'add_shipping_info';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'value',
            'coupon',
            'shipping_tier',
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

    public function setShippingTier(string $tier)
    {
        $this->shipping_tier = $tier;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
    }
}
