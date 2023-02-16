PHP Wrapper for Google Analytics 4 with Server Side Tracking

[![PHP Version](https://img.shields.io/packagist/php-v/alexwestergaard/php-ga4?color=blue&style=for-the-badge)](https://www.php.net/releases/)
[![Release Size](https://img.shields.io/github/languages/code-size/alexwestergaard/php-ga4?color=blue&style=for-the-badge)](https://github.com/AlexWestergaard/php-ga4/releases/latest)
[![Issues](https://img.shields.io/github/issues-raw/alexwestergaard/php-ga4?color=red&style=for-the-badge)](https://github.com/AlexWestergaard/php-ga4/issues)

`composer require alexwestergaard/php-ga4`

- [PHP GA4 | PHP 8+](#php-ga4--php-8)
  - [Events](#events)
    - [Default](#default)
    - [E-commerce](#e-commerce)
    - [Engagement / Gaming](#engagement--gaming)
  - [Frontend \& Backend Communication](#frontend--backend-communication)
    - [Logged/Queued Events](#loggedqueued-events)
    - [Frontend to Backend communication](#frontend-to-backend-communication)
  - [Custom Events](#custom-events)
  - [Documentation](#documentation)

**LEGACY WARNING**
- `PHP 7` should only use `1.0.*` versions of this library

## GDPR Notice

*European Union have noticed that default setup of Google Analytics does not comply with GDPR as data is sent unrestricted to an american service possibly outside of Europe. This includes the use of 'GTAG.js' as JavaScript pushes the request from visitors device including IP-Address. Server Side Tracking, however, does only send information specified inside the body and of your server. Relying solely on Google Analytics 4 Events - that is not pushed through the GTAG.js script - can be scraped of GDPR-related information.*

- Source: Europe, GDPR, Schrems II

## Events

This is a list of prebuilt events as shown in the documentation.

### Default

![badge](https://shields.io/badge/Share-informational)
![badge](https://shields.io/badge/Signup-informational)
![badge](https://shields.io/badge/Login-informational)
![badge](https://shields.io/badge/Search-informational)
![badge](https://shields.io/badge/SelectContent-informational)
![badge](https://shields.io/badge/SelectItem-informational)
![badge](https://shields.io/badge/SelectPromotion-informational)
![badge](https://shields.io/badge/ViewItem-informational)
![badge](https://shields.io/badge/ViewItemList-informational)
![badge](https://shields.io/badge/ViewPromotion-informational)
![badge](https://shields.io/badge/ViewSearchResults-informational)

### E-commerce

![badge](https://shields.io/badge/GenerateLead-informational)
![badge](https://shields.io/badge/AddToWishlist-informational)
![badge](https://shields.io/badge/AddToCart-informational)
![badge](https://shields.io/badge/ViewCart-informational)
![badge](https://shields.io/badge/RemoveFromCart-informational)
![badge](https://shields.io/badge/BeginCheckout-informational)
![badge](https://shields.io/badge/AddPaymentInfo-informational)
![badge](https://shields.io/badge/AddShippingInfo-informational)
![badge](https://shields.io/badge/Purchase-informational)
![badge](https://shields.io/badge/Refund-informational)
  
### Engagement / Gaming

![badge](https://shields.io/badge/EarnVirtualCurrency-informational)
![badge](https://shields.io/badge/SpendVirtualCurrency-informational)
![badge](https://shields.io/badge/LevelUp-informational)
![badge](https://shields.io/badge/PostScore-informational)
![badge](https://shields.io/badge/TutorialBegin-informational)
![badge](https://shields.io/badge/TutorialComplete-informational)
![badge](https://shields.io/badge/UnlockAchievement-informational)
![badge](https://shields.io/badge/JoinGroup-informational)

## Frontend & Backend Communication

This library is built for backend server side tracking, but you will probably trigger most events through frontend with Javascript or Websockets. There will be 2 examples, one as pure backend for logged/queued events and one for frontend to backend communication.
  
### Logged/Queued Events

```php
use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Item;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $analytics = Analytics::new('G-XXXXX', 'secret_api_key')
        ->setClientId('session_id');
        // ^ If gtag.js, this can be the _ga or _gid cookie
    
    if ($user) {
        $analytics->setUserId($user->id);
        // ^ This can be any kind of identifier, readable is easier for you
    }

    $addToCart = Event\AddToCart::new()
        ->setCurrency($cart->currency)
        ->setValue($cart->total);
    
    foreach($cart->products as $product) {
        $addToCart->addItem(
            Item::new()
                ->setItemId($product['id'])
                ->setItemName($product['name'])
                ->setQuantity($product['quantity'])
                ->setPrice($product['price_total'])
                ->setItemVariant($product['colorName'])
        );
    }

    $analytics->addEvent($addToCart);

    // Errors are served as exceptions on pre-exit
    $analytics->post();
} catch (GA4Exception $exception) {
    // Handle exception
    // Exceptions might be stacked, check: $exception->getPrevious();
}

//// ==============================================================
// You can instanciate events with 'fromArray' method as of v1.0.9
// This allows for quick-events by recursive instanciation
$analytics->addEvent(
    Event\AddToCart::fromEvent([
        'currency' => $cart->currency,
        'value' => $cart->total,
        // Items must be array of Items models
        'items' => array_map(
            function ($product) {
                return Item::fromArray([
                    'item_id' => $product->id,
                    'item_name' => $product->name,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                ])
            },
            $cart->products
        ),
    ])
);
//// ==============================================================
```

### Frontend to Backend communication

```js
axios.post('/api/ga4', {
    addToCart: {
        currency: 'EUR',
        value: 13.37,
        items: [
            {
                'item_id': 1,
                'item_name': 'Cup',
                'price': 13.37,
                'quantity': 1
            }
        ]
    }
})
```

```php
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;

try {
    $addToCart = Event\AddToCart::fromArray($_POST['addToCart']);
    Analytics::new($measurementId, $apiSecret)
        ->addEvent($addToCart)
        ->post();
} catch (GA4Exception $exception) {
    // Handle exception
    // Exceptions might be stacked, check: $exception->getPrevious();
}
```

## Custom Events

You can build your own custom events, but be careful to follow this structure. It is important that you extend the Model\Event class because Analytics checks inheritance towards that class on addEvent.

```php
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

## Documentation

- [Measurement Protocol](https://developers.google.com/analytics/devguides/collection/protocol/ga4)
- [Measurement Protocol: Reference](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag)
- [Measurement Protocol: User Properties](https://developers.google.com/analytics/devguides/collection/protocol/ga4/user-properties?client_type=gtag)
- [Measurement Protocol: Events](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events)
  - [Reserved Event Names](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag#reserved_event_names)
- [Measurement Protocol: Validation](https://developers.google.com/analytics/devguides/collection/protocol/ga4/validating-events?client_type=gtag)
