<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use ReflectionClass;
use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade\Type;
use AlexWestergaard\PhpGa4\Facade\Group;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4Test\TestCase;

final class EventTest extends TestCase
{
    public function test_pageview()
    {
        $event = new Event\PageView;

        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_addpaymentinfo()
    {
        $event = new Event\AddPaymentInfo;

        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_addshippinginfo()
    {
        $event = new Event\AddShippingInfo;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_addtocart()
    {
        $event = new Event\AddToCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_addtowishlist()
    {
        $event = new Event\AddToWishlist;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_begincheckout()
    {
        $event = new Event\BeginCheckout;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_earnvirtualcurrency()
    {
        $event = new Event\EarnVirtualCurrency;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_generatelead()
    {
        $event = new Event\GenerateLead;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_joingroup()
    {
        $event = new Event\JoinGroup;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_levelup()
    {
        $event = new Event\LevelUp;

        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }
    public function test_login()
    {
        $event = new Event\Login;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_postscore()
    {
        $event = new Event\PostScore;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_purchase()
    {
        $event = new Event\Purchase;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_refund()
    {
        $event = new Event\Refund;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_refund_full_succeeds_without_items()
    {
        $refund = Event\Refund::new()->setTransactionId(1)->isFullRefund(true);

        $this->analytics->addEvent($refund);

        $this->assertNull($this->analytics->post());
    }

    public function test_refund_partial_succeeds_with_items()
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

    public function test_refund_partial_throws_without_items()
    {
        $refund = Event\Refund::new()->setTransactionId(1);

        $this->expectException(Ga4Exception::class);
        $this->expectExceptionCode(Ga4Exception::PARAM_MISSING_REQUIRED);

        $this->analytics->addEvent($refund);
    }

    public function test_removefromcart()
    {
        $event = new Event\RemoveFromCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_search()
    {
        $event = new Event\Search;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_selectcontent()
    {
        $event = new Event\SelectContent;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_selectitem()
    {
        $event = new Event\SelectItem;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_selectpromotion()
    {
        $event = new Event\SelectPromotion;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_share()
    {
        $event = new Event\Share;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_signup()
    {
        $event = new Event\Signup;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_spendvirtualcurrency()
    {
        $event = new Event\SpendVirtualCurrency;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_tutorialbegin()
    {
        $event = new Event\TutorialBegin;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_tutorialcomplete()
    {
        $event = new Event\TutorialComplete;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_unlockachievement()
    {
        $event = new Event\UnlockAchievement;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_biewcart()
    {
        $event = new Event\ViewCart;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_viewitem()
    {
        $event = new Event\ViewItem;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_viewitemlist()
    {
        $event = new Event\ViewItemList;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_viewpromotion()
    {
        $event = new Event\ViewPromotion;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_viewsearchresults()
    {
        $event = new Event\ViewSearchResults;
        $this->assertEventNaming($event);
        $this->assertEventFills($this->populateEventByMethod(clone $event));
        $this->assertEventFills($this->populateEventByArrayable(clone $event));
        $this->assertEventFills($this->populateEventByFromArray(clone $event));
    }

    public function test_throw_name_missing()
    {
        $mock = new class extends Event\Refund
        {
            public function getName(): string
            {
                return '';
            }
        };

        $class = $mock::new()->setTransactionId(1);

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_MISSING);

        $class->toArray();
    }

    public function test_throw_name_too_long()
    {
        $mock = new class extends Event\Refund
        {
            public function getName(): string
            {
                $tooLongName = '';
                while (mb_strlen($tooLongName) <= 40) {
                    $tooLongName .= range('a', 'z')[rand(0, 25)];
                }
                return $tooLongName;
            }
        };

        $class = $mock::new()->setTransactionId(1);

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_TOO_LONG);

        $class->toArray();
    }

    public function test_throw_name_invalid()
    {
        $mock = new class extends Event\Refund
        {
            public function getName(): string
            {
                return 'WëirdNåme';
            }
        };

        $class = $mock::new()->setTransactionId(1);

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_INVALID);

        $class->toArray();
    }

    public function test_throw_name_reserved()
    {
        $mock = new class extends Event\Refund
        {
            public function getName(): string
            {
                return Type\EventType::RESERVED_NAMES[0];
            }
        };

        $class = $mock::new()->setTransactionId(1);

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_RESERVED);

        $class->toArray();
    }

    protected function assertEventNaming($event)
    {
        $this->assertInstanceOf(Type\EventType::class, $event);
        $this->assertInstanceOf(EventHelper::class, $event);

        $reflection = new ReflectionClass($event);
        $filename = $reflection->getFileName();

        $this->assertFileExists($filename);
        $this->assertEquals(
            strtolower(basename($filename, '.php')),
            strtolower(strtr($event->getName(), ['_' => ''])),
            strtolower(basename($filename, '.php')) . ' is not equal to ' . strtolower(strtr($event->getName(), ['_' => '']))
        );
    }

    protected function assertEventFills(Type\EventType $event)
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

    private function populateEventByFromArray(Type\EventType $event)
    {
        return $event::fromArray([
            'language' => 'en-US',
            'page_location' => '/',
            'page_referrer' => 'https://example.com/',
            'page_title' => 'Home - Site',
            'screen_resolution' => '1920x1080',
            // ---
            'currency' => $this->prefill['currency'],
            'value' => 9.99,
            'affiliation' => 'affiliation',
            'coupon' => 'voucher-code',
            'price' => 9.99,
            'quantity' => 10,
            'payment_type' => 'credit card',
            'tax' => 2.22,
            'shipping' => 3.33,
            'shipping_tier' => 'ground',
            'item_id' => 'item-id',
            'items' => $this->item, // converts into either addItem() or setItem(), will not overwrite as [item]
            'item_list_id' => 'list-id',
            'item_list_name' => 'list-name',
            'virtual_currency_name' => $this->prefill['currency_virtual'],
            'value' => 9.99,
            'item_name' => 'CookieBite',
            'character' => 'AlexWestergaard',
            'level' => 3,
            'score' => 500,
            'location_id' => 'ChIJeRpOeF67j4AR9ydy_PIzPuM',
            'transaction_id' => 'O6435DK',
            'achievement_id' => 'achievement_buy_5_items',
            'group_id' => '999',
            'method' => 'some-method',
            'search_term' => 'search',
            'content_type' => 'content-type',
            'creative_name' => 'creative-name',
            'creative_slot' => 'creative-slot',
            'promotion_id' => 'promotion-id',
            'promotion_name' => 'promotion-name',
        ]);
    }

    private function populateEventByArrayable(Type\EventType $event)
    {
        $event['language'] = 'en-US';
        $event['page_location'] = '/';
        $event['page_referrer'] = 'https://example.com/';
        $event['page_title'] = 'Home - Site';
        $event['screen_resolution'] = '1920x1080';

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
        Type\EventType|Group\AddPaymentInfoFacade|Group\AddShippingInfoFacade|Group\AddToCartFacade|Group\AddToWishlistFacade|Group\AnalyticsFacade|Group\BeginCheckoutFacade|Group\EarnVirtualCurrencyFacade|Group\ExportFacade|Group\GenerateLeadFacade|Group\ItemFacade|Group\JoinGroupFacade|Group\LevelUpFacade|Group\LoginFacade|Group\PostScoreFacade|Group\PurchaseFacade|Group\RefundFacade|Group\RemoveFromCartFacade|Group\SearchFacade|Group\SelectContentFacade|Group\SelectItemFacade|Group\SelectPromotionFacade|Group\ShareFacade|Group\SignUpFacade|Group\SpendVirtualCurrencyFacade|Group\UnlockAchievementFacade|Group\ViewCartFacade|Group\ViewItemFacade|Group\ViewItemListFacade|Group\ViewPromotionFacade|Group\ViewSearchResultsFacade|Group\hasItemsFacade $event
    ) {
        $params = $event->getAllParams();

        $event->setLanguage('en-US');
        $event->setPageLocation('/');
        $event->setPageReferrer('https://example.com/');
        $event->setPageTitle('Home - Site');
        $event->setScreenResolution('1920x1080');

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
