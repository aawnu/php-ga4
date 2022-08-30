# php-ga4
PHP Wrapper for Google Analytics 4 Server Side Tracking of Events.
Pageviews and Cookies [`Cookie._ga` / `Cookie._gid`] are triggered by JavaScript (gtag.js).

```sh
# Add library to your codebase
composer require alexwestergaard/php-ga4
```

[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/alexwestergaard/php-ga4?color=blue&style=for-the-badge)](https://www.php.net/releases/)
[![GitHub Code Size](https://img.shields.io/github/languages/code-size/alexwestergaard/php-ga4?color=blue&style=for-the-badge)](https://github.com/AlexWestergaard/php-ga4/releases/latest)
[![Packagist Stars](https://img.shields.io/packagist/stars/alexwestergaard/php-ga4?color=yellow&style=for-the-badge)](https://github.com/AlexWestergaard/php-ga4/stargazers)
[![Packagist Downloads](https://img.shields.io/packagist/dt/alexwestergaard/php-ga4?color=yellow&style=for-the-badge)](https://packagist.org/packages/alexwestergaard/php-ga4/stats)
[![GitHub issues](https://img.shields.io/github/issues-raw/alexwestergaard/php-ga4?color=red&style=for-the-badge)](https://github.com/AlexWestergaard/php-ga4/issues)

## Default Events
List of all pre-defined events ready to be used as recommended by the Google Analytics Measurement Protocol.

![Share](https://shields.io/badge/Share-informational)
![Signup](https://shields.io/badge/Signup-informational)
![Login](https://shields.io/badge/Login-informational)
![Search](https://shields.io/badge/Search-informational)
![SelectContent](https://shields.io/badge/SelectContent-informational)
![SelectItem](https://shields.io/badge/SelectItem-informational)
![SelectPromotion](https://shields.io/badge/SelectPromotion-informational)
![ViewItem](https://shields.io/badge/ViewItem-informational)
![ViewItemList](https://shields.io/badge/ViewItemList-informational)
![ViewPromotion](https://shields.io/badge/ViewPromotion-informational)
![ViewSearchResults](https://shields.io/badge/ViewSearchResults-informational)

### E-commerce

![GenerateLead](https://shields.io/badge/GenerateLead-informational)
![AddToWishlist](https://shields.io/badge/AddToWishlist-informational)
![AddToCart](https://shields.io/badge/AddToCart-informational)
![ViewCart](https://shields.io/badge/ViewCart-informational)
![RemoveFromCart](https://shields.io/badge/RemoveFromCart-informational)
![BeginCheckout](https://shields.io/badge/BeginCheckout-informational)
![AddPaymentInfo](https://shields.io/badge/AddPaymentInfo-informational)
![AddShippingInfo](https://shields.io/badge/AddShippingInfo-informational)
![Purchase](https://shields.io/badge/Purchase-informational)
![Refund](https://shields.io/badge/Refund-informational)

### Engagement (Gaming?)

![EarnVirtualCurrency](https://shields.io/badge/EarnVirtualCurrency-informational)
![SpendVirtualCurrency](https://shields.io/badge/SpendVirtualCurrency-informational)
![LevelUp](https://shields.io/badge/LevelUp-informational)
![PostScore](https://shields.io/badge/PostScore-informational)
![TutorialBegin](https://shields.io/badge/TutorialBegin-informational)
![TutorialComplete](https://shields.io/badge/TutorialComplete-informational)
![UnlockAchievement](https://shields.io/badge/UnlockAchievement-informational)
![JoinGroup](https://shields.io/badge/JoinGroup-informational)

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

    $viewCart = Event\ViewCart::new()
        ->setCurrency('EUR');

    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['price_total'];
        $product = Item::new()
            ->setItemId($item['id'])
            ->setItemName($item['name'])
            ->setQuantity($item['qty'])
            ->setPrice(round($item['price_total'] / $item['qty'], 2)) // unit price
            ->setItemVariant($item['colorName']);

        $viewCart->addItem($product);
    }

    $viewCart->setValue($totalPrice);

    $analytics->addEvent($viewCart);

    $analytics->post(); // Errors are served as exceptions on pre-exit
} catch (GA4Exception $e) {
    // Handle exception
    // Exceptions might be stacked, check: $e->getPrevious();
}
```

### Request Format
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

## Custom Events
You can build your own custom events by extending `AlexWestergaard\PhpGa4\Model\Event`

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
        return $this; // Allows chained events
    }

    public function setMyRequiredVariable(string $value)
    {
        $this->my_required_variable = $value;
        return $this; // Allows chained events
    }
}
```

It's important that you extend the Model\Event class because Analytics checks inheritance towards that class to ensure we grap all parameters and ensures required parameters.

## Source Documentation
- [Measurement Protocol](https://developers.google.com/analytics/devguides/collection/protocol/ga4)
- [Measurement Protocol: Reference](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag)
- [Measurement Protocol: User Properties](https://developers.google.com/analytics/devguides/collection/protocol/ga4/user-properties?client_type=gtag)
- [Measurement Protocol: Events](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events)
  - [Reserved Event Names](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag#reserved_event_names)
- [Measurement Protocol: Validation](https://developers.google.com/analytics/devguides/collection/protocol/ga4/validating-events?client_type=gtag)