<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class Refund extends Model\Event implements Interface\Refund
{
    protected $currency;
    protected $transaction_id;
    protected $value;
    protected $affiliation;
    protected $coupon;
    protected $shipping;
    protected $tax;
    protected $items = [];

    public function getName(): string
    {
        return 'refund';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'transaction_id',
            'value',
            'affiliation',
            'coupon',
            'shipping',
            'tax',
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

        $return[] = 'transaction_id';
        $return[] = 'items';
        return $return;
    }

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
    }

    public function setTransactionId(string $id)
    {
        $this->transaction_id = $id;
    }

    public function setValue(int|float $val)
    {
        $this->value = $val;
    }

    public function setAffiliation(string $affiliation)
    {
        $this->affiliation = $affiliation;
    }

    public function setCoupon(string $code)
    {
        $this->coupon = $code;
    }

    public function setShipping(int $cost)
    {
        $this->shipping = $cost;
    }

    public function setTax(int $tax)
    {
        $this->tax = $tax;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
    }
}
