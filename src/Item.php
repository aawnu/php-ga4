<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Facade;

/**
 * Structured items for events that touch products
 */
class Item extends Helper\IOHelper implements Facade\Type\ItemType
{
    protected null|string $item_id;
    protected null|string $item_name;
    protected null|string $affiliation;
    protected null|string $coupon;
    protected null|string $currency;
    protected null|string $item_brand;
    protected null|string $item_list_id;
    protected null|string $item_list_name;
    protected null|string $item_variant;
    protected null|string $location_id;
    protected null|int|float $discount;
    protected null|int|float $price;
    protected null|int $quantity;
    protected null|int $index;
    protected array $item_category = [];

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

    public function toArray(): array
    {
        $res = parent::toArray();

        if (!isset($res['item_category'])) {
            return $res;
        }

        $categories = $res['item_category'];
        unset($res['item_category']);

        if (is_array($categories)) {
            foreach ($categories as $k => $val) {
                $tag = 'item_category' . ($k > 0 ? $k + 1 : '');

                $res[$tag] = $val;
            }
        }

        return $res;
    }

    public static function new(): static
    {
        return new static();
    }
}
