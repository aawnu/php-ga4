# php-ga4
PHP Wrapper for Google Analytics 4

## Example
```php
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

// -----------------------------------------
// Analytics
$analytics = new Analytics($measurement_id, $api_secret, $debug);
$analytics->setClientId($client_id);
$analytics->setUserId($user_id);

// -----------------------------------------
// Item
$item = new Item();
$item->setItemId('SKU_1');
$item->setItemBrand('My Awesome Product 3000');
$item->addItemCategory('Awesome');
$item->addItemCategory('2022');
$item->setCurrency('USD');
$item->setPrice(4.99);

// -----------------------------------------
// User Property

$UserProperty = new UserProperty();
$UserProperty->setName('customer_tier');
$UserProperty->setValue('premium');

$analytics->addUserProperty($UserProperty);

// -----------------------------------------
// Event

$addPaymentInfo = new Event\AddPaymentInfo();
$addPaymentInfo->setCurrency('USD');
$addPaymentInfo->setValue(4.99);
$addPaymentInfo->addItem($item);

$analytics->addEvent($addPaymentInfo);

// -----------------------------------------
// Push Analytics to Google for Validation
try {
    $validate = true; // Outputs request + response to/from Google Analytics
    $analytics->post($validate);
} catch (GA4Exception $e) {
    // Handle exception
    print_r($e);
}
```

### Request
```json
{
    "client_id": "GA0.5632897.54363853.TEST",
    "user_id": "GA4_VALIDATE",
    "user_properties": {
        "customer_tier": {
            "value": "premium"
        }
    },
    "events": [
        {
            "name": "add_payment_info",
            "params": {
                "currency": "USD",
                "value": 4.99,
                "items": [
                    {
                        "item_id": "SKU_1",
                        "currency": "USD",
                        "item_brand": "My Awesome Product 3000",
                        "price": 4.99,
                        "item_category": "Awesome",
                        "item_category2": "2022"
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

use AlexWestergaard\PhpGa4\Interface;
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