<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4Test\TestCase;

final class ItemTest extends TestCase
{
    public function test_can_configure_and_export()
    {
        $item = Item::new()
            ->setItemId('1')
            ->setItemName('First Product')
            ->setAffiliation('unit test')
            ->setCoupon('code')
            ->setCurrency($this->prefill['currency'])
            ->setDiscount(1.11)
            ->setIndex(1)
            ->setItemBrand('phpunit')
            ->addItemCategory('unit')
            ->setItemListId('test-list')
            ->setItemListName('test list')
            ->setItemVariant('test')
            ->setLocationId('L1')
            ->setPrice(7.39)
            ->setQuantity(2);

        $asArray = $item->toArray();

        $this->assertIsArray($asArray);
        $this->assertArrayHasKey('item_id', $asArray);
        $this->assertArrayHasKey('item_name', $asArray);
        $this->assertArrayHasKey('affiliation', $asArray);
        $this->assertArrayHasKey('coupon', $asArray);
        $this->assertArrayHasKey('currency', $asArray);
        $this->assertArrayHasKey('item_brand', $asArray);
        $this->assertArrayHasKey('item_list_id', $asArray);
        $this->assertArrayHasKey('item_list_name', $asArray);
        $this->assertArrayHasKey('item_variant', $asArray);
        $this->assertArrayHasKey('location_id', $asArray);
        $this->assertArrayHasKey('discount', $asArray);
        $this->assertArrayHasKey('price', $asArray);
        $this->assertArrayHasKey('quantity', $asArray);
        $this->assertArrayHasKey('index', $asArray);
        $this->assertArrayHasKey('item_category', $asArray);
        $this->assertIsArray($asArray['item_category']);
    }

    public function test_can_configure_arrayable()
    {
        $item = Item::new();

        $item['item_id'] = '1';
        $item['item_name'] = 'First Product';
        $item['affiliation'] = 'unit test';
        $item['coupon'] = 'code';
        $item['currency'] = $this->prefill['currency'];
        $item['item_brand'] = 'phpunit';
        $item['item_list_id'] = 'test-list';
        $item['item_list_name'] = 'test list';
        $item['item_variant'] = 'test';
        $item['location_id'] = 'L1';
        $item['discount'] = 1.11;
        $item['price'] = 7.39;
        $item['quantity'] = 2;
        $item['index'] = 1;
        $item['item_category'] = 'unit';

        $this->assertArrayHasKey('item_id', $item);
        $this->assertArrayHasKey('item_name', $item);
        $this->assertArrayHasKey('affiliation', $item);
        $this->assertArrayHasKey('coupon', $item);
        $this->assertArrayHasKey('currency', $item);
        $this->assertArrayHasKey('item_brand', $item);
        $this->assertArrayHasKey('item_list_id', $item);
        $this->assertArrayHasKey('item_list_name', $item);
        $this->assertArrayHasKey('item_variant', $item);
        $this->assertArrayHasKey('location_id', $item);
        $this->assertArrayHasKey('discount', $item);
        $this->assertArrayHasKey('price', $item);
        $this->assertArrayHasKey('quantity', $item);
        $this->assertArrayHasKey('index', $item);
        $this->assertArrayHasKey('item_category', $item);
        $this->assertIsArray($item['item_category']);
    }

    public function test_can_export_to_array()
    {
        $this->assertInstanceOf(Item::class, $this->item);

        $arr = $this->item->toArray();
        $this->assertIsArray($arr);
        $this->assertArrayHasKey('item_id', $arr);
        $this->assertArrayHasKey('item_name', $arr);
        $this->assertArrayHasKey('currency', $arr);
        $this->assertArrayHasKey('price', $arr);
        $this->assertArrayHasKey('quantity', $arr);
    }

    public function test_can_import_from_array()
    {
        $item = Item::fromArray([
            'item_id' => '2',
            'item_name' => 'Second Product',
            'currency' => $this->prefill['currency'],
            'price' => 9.99,
            'quantity' => 4,
        ]);

        $this->assertInstanceOf(Item::class, $item);

        $arr = $item->toArray();
        $this->assertIsArray($arr);
        $this->assertArrayHasKey('item_id', $arr);
        $this->assertArrayHasKey('item_name', $arr);
        $this->assertArrayHasKey('currency', $arr);
        $this->assertArrayHasKey('price', $arr);
        $this->assertArrayHasKey('quantity', $arr);
    }
}
