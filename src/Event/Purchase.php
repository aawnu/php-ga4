<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class Purchase extends Model\Event implements Facade\Purchase
{
    protected null|string $currency;
    protected null|string $transaction_id;
    protected null|int|float $value;
    protected null|string $affiliation;
    protected null|string $coupon;
    protected null|int|float $shipping;
    protected null|int|float $tax;
    protected array $items = [];

    public function getName(): string
    {
        return 'purchase';
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

    public function setCurrency(null|string $iso)
    {
        $this->currency = $iso;
        return $this;
    }

    public function setTransactionId(null|string $id)
    {
        $this->transaction_id = $id;
        return $this;
    }

    public function setValue(null|int|float $val)
    {
        $this->value = $val;
        return $this;
    }

    public function setAffiliation(null|string $affiliation)
    {
        $this->affiliation = $affiliation;
        return $this;
    }

    public function setCoupon(null|string $code)
    {
        $this->coupon = $code;
        return $this;
    }

    public function setShipping(null|int|float $cost)
    {
        $this->shipping = $cost;
        return $this;
    }

    public function setTax(null|int|float $tax)
    {
        $this->tax = $tax;
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
