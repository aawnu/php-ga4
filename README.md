# php-ga4
PHP Wrapper for Google Analytics 4

## Pre-built Events
List of all pre-defined events ready to be used as recommended by the Google Analytics Measurement Protocol.

- Share
- Signup
- Login
- Search
- SelectContent
- SelectItem
- SelectPromotion
- ViewItem
- ViewItemList
- ViewPromotion
- ViewSearchResults

### E-commerce
- GenerateLead
- AddToWishlist
- AddToCart
- ViewCart
- RemoveFromCart
- BeginCheckout
- AddPaymentInfo
- AddShippingInfo
- Purchase
- Refund

### Engagement (Gaming?)
- EarnVirtualCurrency
- SpendVirtualCurrency
- LevelUp
- PostScore
- TutorialBegin
- TutorialComplete
- UnlockAchievement
- JoinGroup

## Example
```php
<?php

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Item;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $analytics = new Analytics('G-XXXXXXXX', 'gDS1gs423dDSH34sdfa');
    $analytics->setClientId($_COOKIE['_ga'] ?? $_COOKIE['_gid'] ?? $fallback);
    if ($loggedIn) {
        $analytics->setUserId($uniqueUserId);
    }
    
    $viewCart = new Event\ViewCart();
    $viewCart->setCurrency('EUR');
    
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $product = new Item();
        $product->setItemId($item['id']);
        $product->setItemName($item['name']);
        $product->setQuantity($item['qty']);
        $totalPrice += $item['price_total'];
        $product->setPrice(round($item['price_total'] / $item['qty'], 2)); // unit price
        $product->setItemVariant($item['colorName']);
    
        $viewCart->addItem($product);
    }
    
    $viewCart->setValue($totalPrice);
    
    $analytics->addEvent($viewCart);
    
    if (!$analytics->post()) {
        // Handling if post was unsuccessfull
    }
    
    // Handling if post was successfull
} catch (GA4Exception $gErr) {
    // Handle exception
    // Exceptions might be stacked, make sure to check $gErr->getPrevious();
}
```

### Request
```json
{
    "client_id": "GA0.43535.234234",
    "user_id": "m6435",
    "events": [
        {
            "name": "view_cart",
            "params": {
                "currency": "EUR",
                "value": 50.55,
                "items": [
                    {
                        "item_id": "1",
                        "item_name": "product name",
                        "item_variant": "white",
                        "price": 17.79,
                        "quantity": 2
                    },
                    {
                        "item_id": "2",
                        "item_name": "another product name",
                        "item_variant": "gold",
                        "price": 4.99,
                        "quantity": 3
                    }
                ]
            }
        }
    ]
}
```

### Response
```json
{
    "validationMessages": []
}
```

## Custom Events
You can build your own custom events by extending on the Model\Event abstraction class; example

```php
<?php

use AlexWestergaard\PhpGa4\Model;

class ExampleEvent extends Model\Event
{
    protected $my_variable;
    protected $my_required_variable;

    public function getName(): string
    {
        return 'example_event';
    }

    public function getParams(): array
    {
        return [
            'my_variable',
            'my_required_variable',
        ];
    }

    public function getRequiredParams(): array
    {
        return [
            'my_required_variable',
        ];
    }

    public function setMyVariable(string $value)
    {
        $this->my_variable = $value;
    }

    public function setMyRequiredVariable(string $value)
    {
        $this->my_required_variable = $value;
    }
}
```

It's important that you extend the Model\Event class because Analytics checks inheritance towards that class to ensure we grap all parameters and ensures required parameters.

Property name and value will be used as parameter name and value.

Just make sure not to use any [Reserved Event Names](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag#reserved_event_names)

## Dependencies
- [GuzzleHttp/Guzzle: 6.x](https://packagist.org/packages/guzzlehttp/guzzle)
  - Please update [composer.json](composer.json) Guzzle to version 7.x for PHP 8.0+

## Source Documentation
- [Measurement Protocol](https://developers.google.com/analytics/devguides/collection/protocol/ga4)
- [Measurement Protocol: Reference](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag)
- [Measurement Protocol: User Properties](https://developers.google.com/analytics/devguides/collection/protocol/ga4/user-properties?client_type=gtag)
- [Measurement Protocol: Events](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events)
- [Measurement Protocol: Validation](https://developers.google.com/analytics/devguides/collection/protocol/ga4/validating-events?client_type=gtag)