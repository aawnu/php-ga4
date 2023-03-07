<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Analytics;

class LegacyAnalyticsTest extends \PHPUnit\Framework\TestCase
{
    protected $prefill;
    protected $analytics;
    protected $item;

    protected function setUp(): void
    {
        Ga4Exception::resetStack();
        parent::setUp();
        
        $this->prefill = [
            // Analytics
            'measurement_id' => 'G-XXXXXXXX',
            'api_secret' => 'gDS1gs423dDSH34sdfa',
            'client_id' => 'GA0.43535.234234',
            'user_id' => 'm6435',
            // Default Vars
            'currency' => 'EUR',
            'currency_virtual' => 'GA4Coins',
        ];

        $this->analytics = Analytics::new($this->prefill['measurement_id'], $this->prefill['api_secret'], /* DEBUG */ true)
            ->setClientId($this->prefill['client_id'])
            ->setUserId($this->prefill['user_id']);

        $this->item = Item::new()
            ->setItemId('1')
            ->setItemName('First Product')
            ->setCurrency($this->prefill['currency'])
            ->setPrice(7.39)
            ->setQuantity(2);
    }

    public function testAnalytics()
    {
        $this->assertNull($this->analytics->post());
    }

    public function testTimeIsMicrotime()
    {
        $this->analytics->setTimestamp(microtime(true));

        $arr = $this->analytics->toArray();

        $this->assertTrue($arr['timestamp_micros'] > intval(strtr('1_000_000', ['_' => ''])));
    }

    public function testExceptionIfTimeOlderThanOffsetLimit()
    {
        try {
            $this->analytics->setTimestamp(strtotime('-1 week'));
        } catch (Ga4Exception $e) {
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, "Did not receive correct Exception");
        }
    }

    public function testItem()
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

    public function testItemFromArray()
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

    public function testUserProperty()
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

    public function testFullRefundNoItems()
    {
        $refund = Event\Refund::new()->setTransactionId(1)->isFullRefund(true);

        $this->analytics->addEvent($refund);

        $this->assertNull($this->analytics->post());
    }

    public function testPartialRefundWithItems()
    {
        $refund = Event\Refund::new()->setTransactionId(1)->addItem($this->item);

        $this->analytics->addEvent($refund);

        $arr = $this->analytics->toArray();
        $this->assertIsArray($arr);

        $arr = $refund->toArray();
        $this->assertArrayHasKey('params', $arr);
        $arr = $arr['params'];
        $this->assertArrayHasKey('items', $arr);
    }

    public function testPartialRefundNoItemsThrows()
    {
        $refund = Event\Refund::new()->setTransactionId(1);

        $this->expectException(Ga4Exception::class);

        $this->analytics->addEvent($refund);
    }

    public function testPrebuildEvents()
    {
        $getDefaultEventsByFile = glob(__DIR__ . '/../src/Event/*.php');

        foreach ($getDefaultEventsByFile as $file) {
            $eventName = 'AlexWestergaard\\PhpGa4\\Event\\' . basename($file, '.php');

            $this->assertTrue(class_exists($eventName), $eventName);

            $event = new $eventName;
            $required = $event->getRequiredParams();
            $params = array_unique(array_merge($event->getParams(), $required));

            $this->assertEquals(
                strtolower(basename($file, '.php')),
                strtolower(strtr($event->getName(), ['_' => ''])),
                strtolower(basename($file, '.php')) . ' is not equal to ' . strtolower(strtr($event->getName(), ['_' => '']))
            );

            if (in_array('currency', $params)) {
                $event->setCurrency($this->prefill['currency']);
                if (in_array('value', $params)) {
                    $event->setValue(9.99);
                }
            }

            if (in_array('price', $params)) {
                $event->setPrice(9.99);
            }

            if (in_array('quantity', $params)) {
                $event->setQuantity(9.99);
            }

            if (in_array('payment_type', $params)) {
                $event->setPaymentType('credit card');
            }

            if (in_array('shipping_tier', $params)) {
                $event->setShippingTier('ground');
            }

            if (in_array('items', $params)) {
                if (method_exists($event, 'addItem')) {
                    $event->addItem($this->item);
                } elseif (method_exists($event, 'setItem')) {
                    $event->setItem($this->item);
                }
            }

            if (in_array('virtual_currency_name', $params)) {
                $event->setVirtualCurrencyName($this->prefill['currency_virtual']);

                if (in_array('value', $params)) {
                    $event->setValue(9.99);
                }

                if (in_array('item_name', $params)) {
                    $event->setItemName('CookieBite');
                }
            }

            if (in_array('character', $params)) {
                $event->setCharacter('AlexWestergaard');

                if (in_array('level', $params)) {
                    $event->setLEvel(3);
                }

                if (in_array('score', $params)) {
                    $event->setScore(500);
                }
            }

            if (in_array('location_id', $params)) {
                $event->setLocationId('ChIJeRpOeF67j4AR9ydy_PIzPuM');
            }

            if (in_array('transaction_id', $params)) {
                $event->setTransactionId('O6435DK');
            }

            if (in_array('achievement_id', $params)) {
                $event->setAchievementId('achievement_buy_5_items');
            }

            if (in_array('group_id', $params)) {
                $event->setGroupId('999');
            }

            $this->assertIsArray($event->toArray(), $eventName);

            $this->analytics->addEvent($event);
        }

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

    public function testEventArrayable()
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