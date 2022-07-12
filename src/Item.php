<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class Item extends Model\ToArray implements Interface\Export, Interface\Item
{
    protected $item_id;

    public function setItemId(string $id)
    {
        $this->item_id = $id;
    }

    protected $item_name;

    public function setItemName(string $name)
    {
        $this->item_name = $name;
    }

    protected $affiliation;

    public function setAffiliation(string $affiliation)
    {
        $this->affiliation = $affiliation;
    }

    protected $coupon;

    public function setCoupon(string $code)
    {
        $this->coupon = $code;
    }

    protected $currency;

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
    }

    protected $discount;

    public function setDiscount(int|float $amount)
    {
        $this->discount = $amount;
    }

    protected $index;

    public function setIndex(int $i)
    {
        $this->index = $i;
    }

    protected $item_brand;

    public function setItemBrand(string $brand)
    {
        $this->item_brand = $brand;
    }

    protected $item_category = [];

    public function setItemCategory(string $category)
    {
        $this->item_category = $category;
    }

    protected $item_list_id;

    public function setItemListId(string $id)
    {
        $this->item_list_id = $id;
    }

    protected $item_list_name;

    public function setItemListName(string $name)
    {
        $this->item_list_name = $name;
    }

    protected $item_variant;

    public function setItemVariant(string $variant)
    {
        $this->item_variant = $variant;
    }

    protected $location_id;

    public function setLocationId(string $id)
    {
        $this->location_id = $id;
    }

    protected $price;

    public function setPrice(int|float $amount)
    {
        $this->price = $amount;
    }

    protected $quantity;

    public function setQuantity(int $amount)
    {
        $this->quantity = $amount;
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

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        return parent::toArray($isParent, $childErrors);
    }
}
