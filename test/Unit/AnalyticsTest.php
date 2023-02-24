<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Facade\Type\Ga4Exception as TypeGa4Exception;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4Test\Class\TestCase;

final class AnalyticsTest extends TestCase
{
    public function testAnalyticsCanPost()
    {
        $this->assertNull($this->analytics->post());
    }

    public function testAnalyticsCanExportToArray()
    {
        $arr = $this->analytics
            ->addEvent(Event\JoinGroup::fromArray(['group_id' => 1]))
            ->addUserProperty(UserProperty::fromArray(['name' => 'test', 'value' => 'testvalue']))
            ->toArray();

        $this->assertIsArray($arr);

        $this->assertArrayHasKey('non_personalized_ads', $arr);
        $this->assertArrayHasKey('client_id', $arr);
        $this->assertArrayHasKey('user_id', $arr);
        $this->assertArrayHasKey('events', $arr);
        $this->assertArrayHasKey('user_properties', $arr);
    }

    public function testAnalyticsTimestampIsMicrotime()
    {
        $this->analytics->setTimestampMicros(microtime(true));

        $arr = $this->analytics->toArray();

        $this->assertTrue($arr['timestamp_micros'] > 1_000_000);
    }

    public function testAnalyticsThrowsExceptionIfTimestampOlderThanOffsetLimit()
    {
        $this->expectException(Ga4Exception::class);
        $this->expectExceptionCode(TypeGa4Exception::MICROTIME_EXPIRED);

        $this->analytics->setTimestampMicros(strtotime('-1 week'));
    }

    public function testItemExportsToArray()
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

    public function testItemBuildsFromArray()
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

    public function testAnalyticsExportsUserPropertyToArray()
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

        $this->assertNull($this->analytics->post());
    }

    public function testBuildEventFromArray()
    {
        $event = Event\AddToCart::fromArray([
            'currency' => $this->prefill['currency'],
            'value' => rand(1000, 10000) / 100,
            'items' => [$this->item],
        ]);

        $this->assertIsArray($event->toArray(), get_class($event));

        $this->analytics->addEvent($event);

        $this->assertNull($this->analytics->post());
    }

    public function testEventArrayableAccess()
    {
        $event = new Event\AddToCart();
        $event['currency'] = $this->prefill['currency'];
        $event['value'] = ($value = rand(1000, 10000) / 100);

        $this->assertArrayHasKey('currency', $event);
        $this->assertArrayHasKey('value', $event);
        $this->assertArrayHasKey('items', $event);

        $this->assertEquals($this->prefill['currency'], $event['currency']);
        $this->assertEquals($value, $event['value']);
        $this->assertIsArray($event['items']);
    }
}
