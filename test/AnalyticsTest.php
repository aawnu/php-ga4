<?php

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\UserProperty;

class AnalyticsTest extends \PHPUnit\Framework\TestCase
{
    protected $prefill;
    protected $analytics;
    protected $item;

    /**
     * Setting up each test enviroment variables
     */
    protected function setUp(): void
    {
        $this->prefill = [
            // Analytics
            'measurement_id' => 'G-XXXXXXXX',
            'api_secret' => 'gDS1gs423dDSH34sdfa',
            'client_id' => 'GA0.43535.234234',
            'user_id' => 'm6435',
            // Default Vars
            'currency' => 'EUR',
        ];

        $this->analytics = Analytics::new($this->prefill['measurement_id'], $this->prefill['api_secret'], /* DEBUG */ true)
            ->setClientId($this->prefill['client_id'])
            ->setUserId($this->prefill['user_id']);

        $this->item = Item::new()
            ->setItemId('1')
            ->setItemName('First Product')
            ->setCurrency($this->prefill['currency'])
            ->setPrice(10)
            ->setQuantity(2);
    }

    /**
     * Testing that we can send request to Google Analytics with 200 response
     */
    public function testAnalytics(): void
    {
        $this->assertTrue($this->analytics->post());
    }

    /**
     * Testing that out item is properly build
     */
    public function testItem(): void
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

    /**
     * Testing that we can send a User Property
     */
    public function testUserProperty(): void
    {
        $userProperty = UserProperty::new()
            ->setName('customer_tier')
            ->setValue('premium');

        $this->assertInstanceOf(UserProperty::class, $userProperty);
        $this->assertIsArray($userProperty->toArray());

        $this->analytics->addUserProperty($userProperty);

        $arr = $this->analytics->toArray();
        $this->assertArrayHasKey('user_properties', $arr);

        $arr = $arr['user_properties'];
        $this->assertArrayHasKey('customer_tier', $arr);

        $this->assertTrue($this->analytics->post());
    }
}
