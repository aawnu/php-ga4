<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Trait\StaticNew;
use AlexWestergaard\PhpGa4\Helper\AbstractIO;
use AlexWestergaard\PhpGa4\Facade\Type\Item as TypeItem;

class Item extends AbstractIO implements TypeItem
{
    use StaticNew;

    protected $item_id;
    protected $item_name;
    protected $affiliation;
    protected $coupon;
    protected $currency;
    protected $discount;
    protected $index;
    protected $item_brand;
    protected $item_category = [];
    protected $item_list_id;
    protected $item_list_name;
    protected $item_variant;
    protected $location_id;
    protected $price;
    protected $quantity;

    public function setItemId(string $id)
    {
        $this->item_id = $id;
        return $this;
    }

    public function setItemName(string $name)
    {
        $this->item_name = $name;
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

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
        return $this;
    }

    public function setDiscount(int|float $amount)
    {
        $this->discount = $amount;
        return $this;
    }

    public function setIndex(int $i)
    {
        $this->index = $i;
        return $this;
    }

    public function setItemBrand(string $brand)
    {
        $this->item_brand = $brand;
        return $this;
    }

    public function addItemCategory(string $category)
    {
        $this->item_category[] = $category;
        return $this;
    }

    public function setItemListId(string $id)
    {
        $this->item_list_id = $id;
        return $this;
    }

    public function setItemListName(string $name)
    {
        $this->item_list_name = $name;
        return $this;
    }

    public function setItemVariant(string $variant)
    {
        $this->item_variant = $variant;
        return $this;
    }

    public function setLocationId(string $id)
    {
        $this->location_id = $id;
        return $this;
    }

    public function setPrice(int|float $amount)
    {
        $this->price = 0 + $amount;
        return $this;
    }

    public function setQuantity(int $amount)
    {
        $this->quantity = $amount;
        return $this;
    }

    public function getParams(): array
    {
        return [
            'item_id',
            'item_name',
            'affiliation',
            'coupon',
            'currency',
            'discount',
            'index',
            'item_brand',
            'item_category',
            'item_list_id',
            'item_list_name',
            'item_variant',
            'location_id',
            'price',
            'quantity',
        ];
    }

    public function getRequiredParams(): array
    {
        $return = [];

        if (
            (!isset($this->item_id) || empty($this->item_id) && strval($this->item_id) !== '0')
            && (!isset($this->item_name) || empty($this->item_name) && strval($this->item_name) !== '0')
        ) {
            $return = [
                'item_id',
                'item_name',
            ];
        }

        return $return;
    }
}
