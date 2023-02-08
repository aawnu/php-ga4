<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\GA4Exception;

class AddPaymentInfo extends Model\Event implements Facade\AddPaymentInfo
{
    protected $currency;
    protected $value;
    protected $coupon;
    protected $payment_type;
    protected $items = [];

    public function getName(): string
    {
        return 'add_payment_info';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'value',
            'coupon',
            'payment_type',
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

    public function setValue(int|float $val)
    {
        $this->value = $val;
        return $this;
    }

    public function setCoupon(string $code)
    {
        $this->coupon = $code;
        return $this;
    }

    public function setPaymentType(string $type)
    {
        $this->payment_type = $type;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
