<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class AddShippingInfo extends Model\Event implements Facade\AddShippingInfo
{
    protected null|string $currency;
    protected null|int|float $value;
    protected null|string $coupon;
    protected null|string $shipping_tier;
    protected array $items = [];

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
        $this->coupon = $code;
        return $this;
    }

    public function setShippingTier(null|string $tier)
    {
        $this->shipping_tier = $tier;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
