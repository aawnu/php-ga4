<?php

namespace AlexWestergaard\PhpGa4Test;

use PHPUnit\Framework\TestCase as FrameworkTestCase;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Firebase;

class FirebaseTestCase extends FrameworkTestCase
{
    protected Item $item;
    protected Firebase $firebase;
    protected array $prefill;

    protected function setUp(): void
    {
        Ga4Exception::resetStack();
        parent::setUp();

        $this->prefill = [
            // Analytics
            'firebase_app_id' => 'sdha62HsGjk63lKe2hkyLs',
            'api_secret' => 'gDS1gs423dDSH34sdfa',
            'app_instance_id' => 'e85ab98bdbbf3713a74b8f0409363d20',
            'user_id' => 'm6435',
            // Default Vars
            'currency' => 'EUR',
            'currency_virtual' => 'GA4Coins',
        ];

        $this->firebase = Firebase::new($this->prefill['firebase_app_id'], $this->prefill['api_secret'], /* DEBUG */ true)
            ->setAppInstanceId($this->prefill['app_instance_id'])
            ->setUserId($this->prefill['user_id']);

        $this->item = Item::new()
            ->setItemId('1')
            ->setItemName('First Product')
            ->setCurrency($this->prefill['currency'])
            ->setPrice(7.39)
            ->setQuantity(2);
    }
}
