<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use ReflectionClass;
use AlexWestergaard\PhpGa4\Helper\AbstractEvent;
use AlexWestergaard\PhpGa4\Facade\Type\Event as TypeEvent;
use AlexWestergaard\PhpGa4\Facade\Group;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4Test\Class\TestCase;

final class EventsTest extends TestCase
{
    public function testEventAddPaymentInfo()
    {
        $event = new Event\AddPaymentInfo;

        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testAddPaymentInfo()
    {
        $event = new Event\AddPaymentInfo;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testAddShippingInfo()
    {
        $event = new Event\AddShippingInfo;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testAddToCart()
    {
        $event = new Event\AddToCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testAddToWishlist()
    {
        $event = new Event\AddToWishlist;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testBeginCheckout()
    {
        $event = new Event\BeginCheckout;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testEarnVirtualCurrency()
    {
        $event = new Event\EarnVirtualCurrency;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testGenerateLead()
    {
        $event = new Event\GenerateLead;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testJoinGroup()
    {
        $event = new Event\JoinGroup;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testLevelUp()
    {
        $event = new Event\LevelUp;

        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }
    public function testLogin()
    {
        $event = new Event\Login;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testPostScore()
    {
        $event = new Event\PostScore;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testPurchase()
    {
        $event = new Event\Purchase;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testRefund()
    {
        $event = new Event\Refund;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testEventFullRefundRequiresNoItems()
    {
        $refund = Event\Refund::new()->setTransactionId(1)->isFullRefund(true);

        $this->analytics->addEvent($refund);

        $this->assertNull($this->analytics->post());
    }

    public function testEventPartialRefundWithItems()
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

    public function testEventPartialRefundWithoutItemsThrows()
    {
        $refund = Event\Refund::new()->setTransactionId(1);

        $this->expectException(Ga4Exception::class);

        $this->analytics->addEvent($refund);
    }

    public function testRemoveFromCart()
    {
        $event = new Event\RemoveFromCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSearch()
    {
        $event = new Event\Search;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSelectContent()
    {
        $event = new Event\SelectContent;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSelectItem()
    {
        $event = new Event\SelectItem;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSelectPromotion()
    {
        $event = new Event\SelectPromotion;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testShare()
    {
        $event = new Event\Share;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSignup()
    {
        $event = new Event\Signup;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testSpendVirtualCurrency()
    {
        $event = new Event\SpendVirtualCurrency;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testTutorialBegin()
    {
        $event = new Event\TutorialBegin;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testTutorialComplete()
    {
        $event = new Event\TutorialComplete;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testUnlockAchievement()
    {
        $event = new Event\UnlockAchievement;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testViewCart()
    {
        $event = new Event\ViewCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testViewItem()
    {
        $event = new Event\ViewItem;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testViewItemList()
    {
        $event = new Event\ViewItemList;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testViewPromotion()
    {
        $event = new Event\ViewPromotion;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    public function testViewSearchResults()
    {
        $event = new Event\ViewSearchResults;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
    }

    protected function assertEventNaming($event)
    {
        $reflection = new ReflectionClass($event);
        $filename = $reflection->getFileName();

        $this->assertFileExists($filename);
        $this->assertEquals(
            strtolower(basename($filename, '.php')),
            strtolower(strtr($event->getName(), ['_' => ''])),
            strtolower(basename($filename, '.php')) . ' is not equal to ' . strtolower(strtr($event->getName(), ['_' => '']))
        );
    }

    protected function assertEventFills(AbstractEvent $event)
    {
        $fillables = $event->getAllParams();
        $toArray = $event->toArray();

        $this->assertIsArray($toArray);

        if (empty($fillables)) {
            $this->assertEmpty($toArray['params']);
        } else {
            foreach ($fillables as $fillable) {
                $this->assertArrayHasKey($fillable, $toArray['params'], $fillable);
            }
        }
    }

    private function populateEventByArrayable(
        TypeEvent|Group\AddPaymentInfo|Group\AddShippingInfo|Group\AddToCart|Group\AddToWishlist|Group\Analytics|Group\BeginCheckout|Group\EarnVirtualCurrency|Group\Export|Group\GenerateLead|Group\Item|Group\JoinGroup|Group\LevelUp|Group\Login|Group\PostScore|Group\Purchase|Group\Refund|Group\RemoveFromCart|Group\Search|Group\SelectContent|Group\SelectItem|Group\SelectPromotion|Group\Share|Group\SignUp|Group\SpendVirtualCurrency|Group\UnlockAchievement|Group\ViewCart|Group\ViewItem|Group\ViewItemList|Group\ViewPromotion|Group\ViewSearchResults|Group\hasItems $event
    ) {
        $params = $event->getAllParams();

        $event['currency'] = $this->prefill['currency'];
        $event['value'] = 9.99;
        $event['affiliation'] = 'affiliation';
        $event['coupon'] = 'voucher-code';
        $event['price'] = 9.99;
        $event['quantity'] = 10;
        $event['payment_type'] = 'credit card';
        $event['tax'] = 2.22;
        $event['shipping'] = 3.33;
        $event['shipping_tier'] = 'ground';
        $event['item_id'] = 'item-id';
        $event['items'] = $this->item; // converts into either addItem() or setItem(), will not overwrite as [item]
        $event['item_list_id'] = 'list-id';
        $event['item_list_name'] = 'list-name';
        $event['virtual_currency_name'] = $this->prefill['currency_virtual'];
        $event['value'] = 9.99;
        $event['item_name'] = 'CookieBite';
        $event['character'] = 'AlexWestergaard';
        $event['level'] = 3;
        $event['score'] = 500;
        $event['location_id'] = 'ChIJeRpOeF67j4AR9ydy_PIzPuM';
        $event['transaction_id'] = 'O6435DK';
        $event['achievement_id'] = 'achievement_buy_5_items';
        $event['group_id'] = '999';
        $event['method'] = 'some-method';
        $event['search_term'] = 'search';
        $event['content_type'] = 'content-type';
        $event['creative_name'] = 'creative-name';
        $event['creative_slot'] = 'creative-slot';
        $event['promotion_id'] = 'promotion-id';
        $event['promotion_name'] = 'promotion-name';

        return $event;
    }

    private function populateEventByMethod(
        TypeEvent|Group\AddPaymentInfo|Group\AddShippingInfo|Group\AddToCart|Group\AddToWishlist|Group\Analytics|Group\BeginCheckout|Group\EarnVirtualCurrency|Group\Export|Group\GenerateLead|Group\Item|Group\JoinGroup|Group\LevelUp|Group\Login|Group\PostScore|Group\Purchase|Group\Refund|Group\RemoveFromCart|Group\Search|Group\SelectContent|Group\SelectItem|Group\SelectPromotion|Group\Share|Group\SignUp|Group\SpendVirtualCurrency|Group\UnlockAchievement|Group\ViewCart|Group\ViewItem|Group\ViewItemList|Group\ViewPromotion|Group\ViewSearchResults|Group\hasItems $event
    ) {
        $params = $event->getAllParams();

        if (in_array('currency', $params)) {
            $event->setCurrency($this->prefill['currency']);
            if (in_array('value', $params)) {
                $event->setValue(9.99);
            }
        }

        if (in_array('affiliation', $params)) {
            $event->setAffiliation('affiliation');
        }

        if (in_array('coupon', $params)) {
            $event->setCoupon('voucher-code');
        }

        if (in_array('price', $params)) {
            $event->setPrice(9.99);
        }

        if (in_array('quantity', $params)) {
            $event->setQuantity(10);
        }

        if (in_array('payment_type', $params)) {
            $event->setPaymentType('credit card');
        }

        if (in_array('tax', $params)) {
            $event->setTax(2.22);
        }

        if (in_array('shipping', $params)) {
            $event->setShipping(3.33);
        }

        if (in_array('shipping_tier', $params)) {
            $event->setShippingTier('ground');
        }

        if (in_array('item_id', $params)) {
            $event->setItemId('item-id');
        }

        if (in_array('items', $params)) {
            if (method_exists($event, 'addItem')) {
                $event->addItem($this->item);
            } elseif (method_exists($event, 'setItem')) {
                $event->setItem($this->item);
            }
        }

        if (in_array('item_list_id', $params)) {
            $event->setItemListId('list-id');
        }

        if (in_array('item_list_name', $params)) {
            $event->setItemListName('list-name');
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

        if (in_array('method', $params)) {
            $event->setMethod('some-method');
        }

        if (in_array('search_term', $params)) {
            $event->setSearchTerm('search');
        }

        if (in_array('content_type', $params)) {
            $event->setContentType('content-type');
        }

        if (in_array('creative_name', $params)) {
            $event->setCreativeName('creative-name');
        }

        if (in_array('creative_slot', $params)) {
            $event->setCreativeSlot('creative-slot');
        }

        if (in_array('promotion_id', $params)) {
            $event->setPromotionId('promotion-id');
        }

        if (in_array('promotion_name', $params)) {
            $event->setPromotionName('promotion-name');
        }

        return $event;
    }
}
