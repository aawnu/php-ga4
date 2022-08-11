<?php

use PHPUnit\Framework\TestCase;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\UserProperty;

final class AnalyticsTest extends TestCase
{
    private Analytics $analytics;
    private int $itemInt = 0;
    private $faker;

    protected function setUp(): void
    {
        $this->itemInt = 0;
        $this->faker = Faker\Factory::create();

        $this->analytics = Analytics::new(
            'G-XXX',
            $this->faker->md5,
            true
        )->setClientId($this->faker->md5)
            ->setUserId($this->faker->md5);
    }

    public function testAnalyticsThrowOnWrongMeasurementFormat()
    {
        $this->expectException(GA4Exception::class);

        new Analytics(
            'WRONG-ID',
            $this->faker->md5,
            true
        );
    }

    public function testAnalyticsDebugResponse()
    {
        $this->assertTrue($this->analytics->post());
    }

    public function testAnalyticsUnixTimeFormat()
    {
        $time = time();
        $microtime = $time * 1_000_000;
        $analytics = $this->analytics->setTimestamp($time)->toArray();

        $this->assertIsArray($analytics);
        $this->assertArrayHasKey('timestamp_micros', $analytics);
        $this->assertEquals($microtime, $analytics['timestamp_micros']);
        $this->assertTrue($microtime <= $analytics['timestamp_micros']);
    }

    public function testAnalyticsMicroTimeFormat()
    {
        $time = microtime(true);
        $microtime = $time * 1_000_000;
        $analytics = $this->analytics->setTimestamp($time)->toArray();

        $this->assertIsArray($analytics);
        $this->assertArrayHasKey('timestamp_micros', $analytics);
        $this->assertEquals($microtime, $analytics['timestamp_micros']);
    }

    public function testRandomItemFill()
    {
        $item = $this->getItem();

        $this->assertInstanceOf(Item::class, $item);

        $arr = $item->toArray();
        $this->assertIsArray($arr);
        $this->assertArrayHasKey('item_id', $arr);
        $this->assertArrayHasKey('item_name', $arr);
        $this->assertArrayHasKey('currency', $arr);
        $this->assertArrayHasKey('price', $arr);
        $this->assertArrayHasKey('quantity', $arr);
    }

    public function testExampleUserProperty()
    {
        $userProperty = UserProperty::new()
            ->setName('customer_tier')
            ->setValue('premium');

        $this->assertInstanceOf(UserProperty::class, $userProperty);
        $this->assertTrue(is_array($userProperty->toArray()));

        $this->analytics->addUserProperty($userProperty);

        $arr = $this->analytics->toArray();
        $this->assertArrayHasKey('user_properties', $arr);

        $arr = $arr['user_properties'];
        $this->assertArrayHasKey('customer_tier', $arr);

        $this->assertTrue($this->analytics->post());
    }

    public function testAllRecommendedEvents()
    {
        $eventCount = 0;
        foreach (glob(__DIR__ . '/../src/Event/*.php') as $file) {
            $eventName = 'AlexWestergaard\\PhpGa4\\Event\\' . basename($file, '.php');

            $this->assertTrue(class_exists($eventName), $eventName);

            $event = new $eventName;
            $required = $event->getRequiredParams();
            $params = array_unique(array_merge($event->getParams(), $required));

            try {
                $this->assertEquals(
                    strtolower(basename($file, '.php')),
                    strtolower(strtr($event->getName(), ['_' => ''])),
                    strtolower(basename($file, '.php')) . ' is not equal to ' . strtolower(strtr($event->getName(), ['_' => '']))
                );

                if (in_array('currency', $params)) {
                    $event->setCurrency($this->faker->currencyCode);
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
                        $event->addItem($this->getItem());
                    } elseif (method_exists($event, 'setItem')) {
                        $event->setItem($this->getItem());
                    }
                }

                if (in_array('virtual_currency_name', $params)) {
                    $event->setVirtualCurrencyName('gamecoins');

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

                $this->assertTrue(is_array($event->toArray()), $eventName);

                $this->analytics->addEvent($event);
                $eventCount += 1;
            } catch (throwable $t) {
                $this->assertTrue(false, $t->getFile() . ':' . $t->getLine() . ' > ' . $t->getMessage());
            }

            if ($eventCount >= 25) {
                try {
                    $this->assertTrue($this->analytics->post());
                } catch (throwable $t) {
                    $this->assertTrue(false, $t->getFile() . ':' . $t->getLine() . ' > ' . $t->getMessage());
                } finally {
                    $eventCount = 1;
                    $this->setup();
                }
            }
        }

        if ($eventCount > 0) {
            try {
                $this->assertTrue($this->analytics->post());
            } catch (throwable $t) {
                $this->assertTrue(false, $t->getFile() . ':' . $t->getLine() . ' > ' . $t->getMessage());
            }
        }
    }

    private function getItem()
    {
        return Item::new()
            ->setItemId((string)($this->itemInt += 1))
            ->setItemName($this->faker->title())
            ->setCurrency($this->faker->currencyCode())
            ->setPrice($this->faker->numberBetween(500) / 100)
            ->setQuantity($this->faker->numberBetween(1, 15));
    }
}
