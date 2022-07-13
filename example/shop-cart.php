<?php

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event\AddToCart;
use AlexWestergaard\PhpGa4\Event\RemoveFromCart;
use AlexWestergaard\PhpGa4\Event\ViewCart;
use AlexWestergaard\PhpGa4\Item;

require_once __DIR__ . '/../vendor/autoload.php';

// ================================================
// Variables

$measurement_id = 'G-XXXXXX';
$api_secret = md5('GA4');
$client_id = 'GA0.5632897.54363853.TEST';
$user_id = 'GA4_VALIDATE';

$shopCurrency = 'EUR';

// ================================================
// Prepare Product/Item

$product = new Item();
$product->setItemId(1372);
$product->setItemName('Racing Bike 3000');
$product->setQuantity(7);
$product->setPrice(1250);
$product->setCurrency($shopCurrency);
$product->addItemCategory('Bike');
$product->addItemCategory('Racing');

// ================================================
// Initiate Analytics

$analytics = new Analytics($measurement_id, $api_secret, /* DEBUG */ true);
$analytics->setClientId($client_id);

if ($user_id) {
    $analytics->setUserId($user_id);
}

// ================================================
// Add product to the Cart

$addToCart = new AddToCart();
$viewCart->addItem($product);

// ================================================
// Viewing the Cart

$viewCart = new ViewCart();
$viewCart->setCurrency($shopCurrency);
$viewCart->setValue(960); // Total
$viewCart->addItem($product);

// ================================================
// Remove product from the Cart

$removeFromCart = new RemoveFromCart();
$removeFromCart->addItem($product);
