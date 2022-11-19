<h1 style="text-align:center">PHP GA4</h1>

<p style="text-align:center">PHP Wrapper for Google Analytics 4 Server Side Tracking of events.</p>

<p style="text-align:center">
    <a href="https://www.php.net/releases/" target="_blank">
        <img src="https://img.shields.io/packagist/php-v/alexwestergaard/php-ga4?color=blue&style=for-the-badge"></a>
    <a href="https://github.com/AlexWestergaard/php-ga4/releases/latest" target="_blank">
        <img src="https://img.shields.io/github/languages/code-size/alexwestergaard/php-ga4?color=blue&style=for-the-badge"></a>
    <a href="https://github.com/AlexWestergaard/php-ga4/issues" target="_blank">
        <img src="https://img.shields.io/github/issues-raw/alexwestergaard/php-ga4?color=red&style=for-the-badge"></a>
</p>

<p style="text-align:center">
    <code>composer require alexwestergaard/php-ga4</code>
</p>

<br>

<h2 style="text-align:center">Events</h2>

<p style="text-align:center">
    This is a list of prebuilt events as shown in the documentation |
    <a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events" target="_blank">Measurement Protocol: Events</a><br>
</p>

<h3 style="text-align:center">Default</h3>

<p style="text-align:center">
    <img src="https://shields.io/badge/Share-informational">
    <img src="https://shields.io/badge/Signup-informational">
    <img src="https://shields.io/badge/Login-informational">
    <img src="https://shields.io/badge/Search-informational">
    <img src="https://shields.io/badge/SelectContent-informational">
    <img src="https://shields.io/badge/SelectItem-informational">
    <img src="https://shields.io/badge/SelectPromotion-informational">
    <img src="https://shields.io/badge/ViewItem-informational">
    <img src="https://shields.io/badge/ViewItemList-informational">
    <img src="https://shields.io/badge/ViewPromotion-informational">
    <img src="https://shields.io/badge/ViewSearchResults-informational">
</p>

<h3 style="text-align:center">E-commerce</h3>

<p style="text-align:center">
    <img src="https://shields.io/badge/GenerateLead-informational">
    <img src="https://shields.io/badge/AddToWishlist-informational">
    <img src="https://shields.io/badge/AddToCart-informational">
    <img src="https://shields.io/badge/ViewCart-informational">
    <img src="https://shields.io/badge/RemoveFromCart-informational">
    <img src="https://shields.io/badge/BeginCheckout-informational">
    <img src="https://shields.io/badge/AddPaymentInfo-informational">
    <img src="https://shields.io/badge/AddShippingInfo-informational">
    <img src="https://shields.io/badge/Purchase-informational">
    <img src="https://shields.io/badge/Refund-informational">
</p>
  
<h3 style="text-align:center">Engagement / Gaming</h3>

<p style="text-align:center">
    <img src="https://shields.io/badge/EarnVirtualCurrency-informational">
    <img src="https://shields.io/badge/SpendVirtualCurrency-informational">
    <img src="https://shields.io/badge/LevelUp-informational">
    <img src="https://shields.io/badge/PostScore-informational">
    <img src="https://shields.io/badge/TutorialBegin-informational">
    <img src="https://shields.io/badge/TutorialComplete-informational">
    <img src="https://shields.io/badge/UnlockAchievement-informational">
    <img src="https://shields.io/badge/JoinGroup-informational">
</p>

<br>

<h2 style="text-align:center">Frontend & Backend Communication</h2>

<p style="text-align:center">
    This library is built for backend server side tracking, but you will probably trigger most events through frontend with Javascript or Websockets. There will be 2 examples, one as pure backend for logged/queued events and one for frontend to backend communication.
</p>
  
<h3 style="text-align:center">Logged/Queued Events</h3>

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

<h3 style="text-align:center">Frontend to Backend communication</h3>

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

<h2 style="text-align:center">Custom Events</h2>

<p style="text-align:center">
    You can build your own custom events, but be careful to follow this structure.
    It is important that you extend the Model\Event class because Analytics checks inheritance towards that class on addEvent.
</p>

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

<h2 style="text-align:center">Documentation</h2>

<ul>
    <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4" target="_blank">Measurement Protocol</a></li>
    <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag" target="_blank">Measurement Protocol: Reference</a></li>
    <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/user-properties?client_type=gtag" target="_blank">Measurement Protocol: User Properties</a></li>
    <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events" target="_blank">Measurement Protocol: Events</a></li>
    <ul>
        <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag#reserved_event_names" target="_blank">Reserved Event Names</a></li>
    </ul>
    <li><a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/validating-events?client_type=gtag" target="_blank">Measurement Protocol: Validation</a></li>
</ul>