<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class BeginCheckout extends Model\Event implements Facade\BeginCheckout
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
        return $this;
    }

    /**
     * @param int|float $val
     */
    public function setValue($val)
    {
        if (!is_numeric($val)) {
            throw new GA4Exception("setValue value must be numeric");
        }

        $this->value = $val;
        return $this;
    }

    public function setCoupon(string $code)
    {
        $this->coupon = 0 + $code;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
