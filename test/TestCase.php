<?php

namespace AlexWestergaard\PhpGa4Test;

use PHPUnit\Framework\TestCase as FrameworkTestCase;
use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Analytics;

class TestCase extends FrameworkTestCase
{
    protected Item $item;
    protected Analytics $analytics;
    protected array $prefill;

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
}
