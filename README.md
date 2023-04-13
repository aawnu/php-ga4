_Package_

[![Version](https://img.shields.io/packagist/v/alexwestergaard/php-ga4?color=blue&label=stable%20release)](https://github.com/aawnu/php-ga4/releases/latest)
[![License](https://img.shields.io/packagist/l/alexwestergaard/php-ga4?color=blue)](https://github.com/aawnu/php-ga4/blob/master/LICENSE)
[![PHPVersion](https://img.shields.io/packagist/php-v/alexwestergaard/php-ga4?color=blue)](https://www.php.net/releases)
[![Size](https://img.shields.io/github/languages/code-size/aawnu/php-ga4?color=blue)](https://github.com/aawnu/php-ga4/releases/latest)
![Code Coverage Badge](https://raw.githubusercontent.com/AlexWestergaard/php-ga4/image-data/coverage.svg)

_Development_

[![Version](https://img.shields.io/packagist/v/alexwestergaard/php-ga4?color=red&include_prereleases&label=latest%20release)](https://github.com/aawnu/php-ga4/releases)
[![Issues](https://img.shields.io/github/issues-raw/alexwestergaard/php-ga4?color=red&label=issues)](https://github.com/aawnu/php-ga4/issues)
[![Pulls](https://img.shields.io/github/issues-pr/aawnu/php-ga4?color=red&label=pulls)](https://github.com/aawnu/php-ga4/pulls)
[![Contributors](https://img.shields.io/github/contributors/aawnu/php-ga4?color=red)](https://github.com/aawnu/php-ga4/graphs/contributors)
[![LastCommit](https://img.shields.io/github/last-commit/aawnu/php-ga4/master?color=red)](https://github.com/aawnu/php-ga4/commits)

```sh
composer require alexwestergaard/php-ga4
```

- [GDPR Notice](#gdpr-notice)
- [Getting started](#getting-started)
- [Events](#events)
  - [Default](#default)
  - [E-commerce](#e-commerce)
  - [Engagement / Gaming](#engagement--gaming)
- [Frontend \& Backend Communication](#frontend--backend-communication)
  - [Logging / Queues](#logging--queues)
  - [Frontend =\> Backend](#frontend--backend)
    - [Frontend](#frontend)
    - [Backend](#backend)
- [Custom Events](#custom-events)
- [Documentation](#documentation)

## GDPR Notice

European Union have noticed that default setup of Google Analytics does not comply with GDPR as data is sent unrestricted to an american service possibly outside of Europe.
This includes the use of `gtag.js` as JavaScript pushes the request from visitors device including their IP-Address.

Server Side Tracking, however, does only send information specified inside the body and about your server.

Relying solely on Google Analytics 4 Events - that is not pushed through the `gtag.js` script - can be scraped of GDPR-related information.

- Source: Europe, GDPR, Schrems II
- https://support.google.com/analytics/answer/9019185?hl=en

## Getting started

To setup Analytics you need a Measurement ID and API Secret.

Go to `Administrator` (bottom left) and then select your `Account` -> `Data Streams` -> your stream.  
Here you will find `Measurement-ID` at top from and further down `Api Secrets for Measurement Protocol`, in there you can create yourself an `API Secret`.

**PLEASE** note that Google Analytics will look out from traffic gathered by the `gtag.js` library, as Server Side Events are supposed to supplement the frontend through its `_ga`/`_gid` cookie sessions.

```php
use AlexWestergaard\PhpGa4\Analytics;

$analytics = Analytics::new(
    measurement_id: 'G-XXXXXXXX',
    api_secret: 'xYzzX_xYzzXzxyZxX',
    debug: true/false
);
```

## Events

This is a list of prebuilt events as shown in the documentation. All events have the following parameters to locate trigger location of each event or if they were related to any campaigns/referrals.

```php
// Manual setting of each event
$event->setLanguage(string $var);
$event->setPageLocation(string $var);
$event->setPageReferrer(string $var);
$event->setPageTitle(string $var);
$event->setScreenResolution(string $var);
// Fillable for multiple events
$eventPage = AlexWestergaard\PhpGa4\Helper\EventParamsHelper();
$event->setEventPage($eventPage);
```

```php
// Campaign parameters (Experimental)
$campaign = AlexWestergaard\PhpGa4\Campaign()
$event->setCampaign($campaign);
```

### Default

![badge](https://shields.io/badge/PageView-informational)
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

### Logging / Queues

```php

use AlexWestergaard\PhpGa4\Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Item;

// require vendor/autoload.php

// If gtag.js, this can be the _ga or _gid cookie
// This can be any kind of session identifier
$session = $_COOKIE['_ga'] ?? $_COOKIE['_gid'] ?? $_COOKIE['PHPSESSID'];

// Render events grouped on time
foreach ($groups as $time => $data) {
    try {
            $analytics = Analytics::new($measurementId, $apiSecret)
                ->setClientId($session)
                ->setTimestampMicros($time);

            // load logged in user/visitor
            if ($auth) {
                // This can be any kind of identifier, readable is easier for you
                // Just be wary not to use GDPR sensitive information
                $analytics->setUserId($auth->id);
            }

            $analytics->addUserParameter(...$data['userParameters']);
            $analytics->addEvent(...$data['events']);

            $analytics->post();
    } catch (Exception\Ga4Exception $exception) {
        // Handle exception
        // Exceptions might be stacked, check: $exception->getPrevious();
    }
}
```

### Frontend => Backend

#### Frontend

```js
// array<array<eventName,eventParams>>
axios.post(
    '/api/ga4',
    [
        // Note each event is its own object inside an array as
        // this allows to pass the same event type multiple times
        {
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
        }
    ]
)
```

#### Backend

```php
use AlexWestergaard\PhpGa4\Helper\ConvertHelper;
use AlexWestergaard\PhpGa4\Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;

// require vendor/autoload.php

try {
    $events = ConvertHelper::parseEvents($_POST);

    Analytics::new($measurementId, $apiSecret)
        ->addEvent(...$events)
        ->post();
} catch (Exception\Ga4Exception $exception) {
    // Handle exception
    // Exceptions might be stacked, check: $exception->getPrevious();
}
```

## Custom Events

You can build your own custom events. All you need is to implement and fullfill the `AlexWestergaard\PhpGa4\Facade\Type\EventType` facade/interface. If you want ease of life features, then you can extend your event from `AlexWestergaard\PhpGa4\Helper\EventHelper` and overwrite as you see fit.

```php

// EventHelper implements AlexWestergaard\PhpGa4\Facade\Type\EventType
class ExampleEvent extends AlexWestergaard\PhpGa4\Helper\EventHelper
{
    // variables should be nullable as unset() will set variable as null
    protected null|mixed $my_variable;
    protected null|mixed $my_required_variable;
    
    // Arrays should always be instanciated empty
    protected array $my_array = [];

    public function getName(): string
    {
        return 'example_event';
    }

    public function getParams(): array
    {
        return [
            'my_variable',
            'my_array',
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
