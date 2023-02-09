<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class Refund extends Model\Event implements Facade\Refund
{
    protected $currency;
    protected $transaction_id;
    protected $value;
    protected $affiliation;
    protected $coupon;
    protected $shipping;
    protected $tax;
    protected $items = [];

    private $isFullRefund = false;

    /**
     * Full refunds does not require items to be passed. \
     * This will skip the items check if true
     */
    public function isFullRefund(bool $is)
    {
        $this->isFullRefund = $is;
        return $this;
    }

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

        if (!$this->isFullRefund) {
            $return[] = 'items';
        }

        return $return;
    }

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
        return $this;
    }

    public function setTransactionId(string $id)
    {
        $this->transaction_id = $id;
        return $this;
    }

    public function setValue(int|float $val)
    {
        $this->value = $val;
        return $this;
    }

    public function setAffiliation(string $affiliation)
    {
        $this->affiliation = $affiliation;
        return $this;
    }

    public function setCoupon(string $code)
    {
        $this->coupon = $code;
        return $this;
    }

    public function setShipping(int|float $cost)
    {
        $this->shipping = $cost;
        return $this;
    }

    public function setTax(int|float $tax)
    {
        $this->tax = $tax;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
