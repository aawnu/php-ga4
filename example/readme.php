<?php

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Item;

require_once __DIR__ . '/../vendor/autoload.php';

$measurement_id = 'G-XXXXXX';
$api_secret = md5('GA4');
$debug = true; // Ensures we don't push live data

$client_id = 'GA0.5632897.54363853.TEST';
$user_id = 'GA4_VALIDATE';

// ================================================
// Analytics
$analytics = new Analytics($measurement_id, $api_secret, $debug);
$analytics->setClientId($client_id);
$analytics->setUserId($user_id);

// ================================================
// Item
$item = new Item();
$item->setItemId('SKU_1');
$item->setItemBrand('My Awesome Product 3000');
$item->addItemCategory('Awesome');
$item->addItemCategory('2022');
$item->setCurrency('USD');
$item->setPrice(4.99);

// ================================================
// User Property

$UserProperty = new UserProperty();
$UserProperty->setName('customer_tier');
$UserProperty->setValue('premium');

$analytics->addUserProperty($UserProperty);

// ================================================
// Event

$addPaymentInfo = new Event\AddPaymentInfo();
$addPaymentInfo->setCurrency('USD');
$addPaymentInfo->setValue(4.99);
$addPaymentInfo->addItem($item);

$analytics->addEvent($addPaymentInfo);

// ================================================
// Push Analytics to Google for Validation
try {
    $validate = true; // Outputs request + response to/from Google Analytics
    $analytics->post($validate);
} catch (GA4Exception $e) {
    // Handle exception
    print_r($e);
}