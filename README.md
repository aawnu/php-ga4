# Google Analytics 4 Server-Side PHP Package

[![Version](https://img.shields.io/packagist/v/alexwestergaard/php-ga4?color=blue&label=stable%20release)](https://github.com/aawnu/php-ga4/releases/latest)
[![Version](https://img.shields.io/packagist/v/alexwestergaard/php-ga4?color=yellow&include_prereleases&label=latest%20release)](https://github.com/aawnu/php-ga4/releases)
![Code Coverage Badge](https://raw.githubusercontent.com/AlexWestergaard/php-ga4/image-data/coverage.svg)
[![PHPVersion](https://img.shields.io/packagist/php-v/alexwestergaard/php-ga4?color=blue)](https://www.php.net/releases)
[![Size](https://img.shields.io/github/languages/code-size/aawnu/php-ga4?color=blue)](https://github.com/aawnu/php-ga4/releases/latest) [![Contributors](https://img.shields.io/github/contributors/aawnu/php-ga4?color=blue)](https://github.com/aawnu/php-ga4/graphs/contributors)
[![Issues](https://img.shields.io/github/issues-raw/alexwestergaard/php-ga4?color=red&label=issues)](https://github.com/aawnu/php-ga4/issues)
[![Pulls](https://img.shields.io/github/issues-pr/aawnu/php-ga4?color=red&label=pulls)](https://github.com/aawnu/php-ga4/pulls)
[![LastCommit](https://img.shields.io/github/last-commit/aawnu/php-ga4/master?color=red)](https://github.com/aawnu/php-ga4/commits)

```sh
composer require alexwestergaard/php-ga4
```

## Europe - GDPR Notice

The European Union have notified that Google Analytics does not comply with GDPR by default. This is because the frontend Client sends visitor details like their IP Address and device information with events. This can be avoided with a middle-man server inside the European Region.

- Source: Europe, GDPR, Schrems II
- Options: [Privacy controls in Google Analytics](https://support.google.com/analytics/answer/9019185?hl=en)

## Getting started

Setup requires a **Measurement ID** and **API Secret**. Go to Administrator (Bottom left) -> Account -> Data Streams -> {Your Stream}. Here you should find Measurement ID at top and "Api Secrets for Measurement Protocol" a little down the page, where you can create yourself an `API secret`.

Go to `Administrator` (bottom left) and then select your `Account` -> `Data Streams` -> your stream.
Here you will find `Measurement-ID` at top from and further down `Api Secrets for Measurement Protocol`, in there you can create yourself an `API Secret`.

Once you have obtained the credentials, you can initialise the Analytics like this:

```php
use AlexWestergaard\PhpGa4\Analytics;

$analytics = Analytics::new(
    measurement_id: 'G-XXXXXXXX',
    api_secret: 'xYzzX_xYzzXzxyZxX',
    debug: true|false #Default: False
);
```

### Data flow

Server Side Tagging is not supposed to replace the frontend Client and session initiation should happen through the `gtag.js` Client. The default flow is supposed to happen as follows:

1. Obtain proper GDPR Consent
2. Client/GTAG.js initiates session with Google Analytics
3. Google Analytics sends `_ga` and `_gid` cookies back to Client/GTAG.js
4. Server uses `_ga` (or `_gid`) to send/populate events
   - Eg. GenerateLead, Purchase, Refund and other backend handled events.

Note: It is entirely possible to push events to backend without acquiring the session cookies from Google Analytics; you will, however, lose information bundled inside the `gtag.js` request if you do not figure out how to push that via backend too. You can replace the `_ga` and `_gid` sessions with your own uniquely generated id.

All requests should follow this structure and contain at least 1 event for Google Analytics to accept it.

```txt
Analytics [
    Events [
        Event {
            Parameters
            ? Items [
                Item Parameters
            ]
        }
    ]
    User Properties [
        Properties {
            Key: Value
        }
    ]
    ? Consent {
      Key: Value
    }
    ? User Data {
      Key: Value
    }
]
```

## Events

This is a list of prebuilt events as shown in the documentation. All events have the following parameters to locate trigger location of each event.

### Default

- [PageView](src/Event/PageView.php)
- [Share](src/Event/Share.php)
- [Signup](src/Event/Signup.php)
- [Login](src/Event/Login.php)
- [Search](src/Event/Search.php)
- [SelectContent](src/Event/SelectContent.php)
- [SelectItem](src/Event/SelectItem.php)
- [SelectPromotion](src/Event/SelectPromotion.php)
- [ViewItem](src/Event/ViewItem.php)
- [ViewItemList](src/Event/ViewItemList.php)
- [ViewPromotion](src/Event/ViewPromotion.php)
- [ViewSearchResults](src/Event/ViewSearchResults.php)

### E-commerce

- [GenerateLead](src/Event/GenerateLead.php)
- [AddToWishlist](src/Event/AddToWishlist.php)
- [AddToCart](src/Event/AddToCart.php)
- [ViewCart](src/Event/ViewCart.php)
- [RemoveFromCart](src/Event/RemoveFromCart.php)
- [BeginCheckout](src/Event/BeginCheckout.php)
- [AddPaymentInfo](src/Event/AddPaymentInfo.php)
- [AddShippingInfo](src/Event/AddShippingInfo.php)
- [Purchase](src/Event/Purchase.php)
- [Refund](src/Event/Refund.php)

### Engagement / Gaming

- [EarnVirtualCurrency](src/Event/EarnVirtualCurrency.php)
- [SpendVirtualCurrency](src/Event/SpendVirtualCurrency.php)
- [LevelUp](src/Event/LevelUp.php)
- [PostScore](src/Event/PostScore.php)
- [TutorialBegin](src/Event/TutorialBegin.php)
- [TutorialComplete](src/Event/TutorialComplete.php)
- [UnlockAchievement](src/Event/UnlockAchievement.php)
- [JoinGroup](src/Event/JoinGroup.php)

### Reporting

- [Exception](src/Event/Exception.php)

## Frontend & Backend Communication

This library is built for backend server side tracking, but you will probably trigger most events through frontend with Javascript or Websockets.
There will be 2 examples, one as pure backend for logged/queued events and one for frontend to backend communication.

### Logging / Queue

```php
// require vendor/autoload.php

use AlexWestergaard\PhpGa4\Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Item;

// pseudo function, make your own logic here
$visitors = getVisitorsWithEvents();

foreach ($visitors as $visitor) {
    // Group of events, perhaps need logic to change from json or array to event objects
    // Maybe its formatted well for the > ConvertHelper::parseEvents([...]) < helper
    $groups = $visitor['events'];

    // If gtag.js, this can be the _ga or _gid cookie
    // This can be any kind of session identifier
    // Usually derives from $_COOKIE['_ga'] or $_COOKIE['_gid'] set by GTAG.js
    $client = $visitor['client_id'];

    // load logged in user/visitor
    // This can be any kind of unique identifier, readable is easier for you
    // Just be wary not to use GDPR sensitive information
    $user = $visitor['user_id'];
    $userParameters = $visitor["user_parameters"]

    // Render events grouped on time (max offset is 3 days from NOW)
    foreach ($groups as $time => $data) {
        try {
                $analytics = Analytics::new($measurementId, $apiSecret)
                    ->setClientId($client)
                    ->setTimestampMicros($time);

                if ($user !== null) {
                    $analytics->setUserId($user);
                }

                // pseudo logic for adding user parameters
                $analytics->addUserParameter(...$userParameters);
                // pseudo logic for adding events
                $analytics->addEvent(...$data['events']);

                // send events to Google Analytics
                $analytics->post();
        } catch (Exception\Ga4Exception $exception) {
            // Handle exception
            // Exceptions might be stacked, check: $exception->getPrevious();
            ExceptionTrail(function(Exception $e) {/*...*/}, $exception)
        }
    }
}

// pseudo function to trail the exception tree
function ExceptionTrail(callable $handler, Exception $e) {
    $handler($e);

    $prev = $e->getPrevious();
    if ($prev instanceof Exception) {
        ExceptionTrail($handler, $prev);
    }
}

```

## Custom Events

You can build your own custom events. All you need is to implement and fullfill the `AlexWestergaard\PhpGa4\Facade\Type\EventType` facade/interface.
If you want quality of life features, then you can extend your event from `AlexWestergaard\PhpGa4\Helper\EventHelper` or `AlexWestergaard\PhpGa4\Helper\EventMainHelper` and overwrite as you see fit.

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

## Debug

Measurement protocol for GA4 has debug functionality that can be enabled with the `debug` parameter in the Analytics constructor.

```php
$analytics = Analytics::new(
    measurement_id: 'G-XXXXXXXX',
    api_secret: 'xYzzX_xYzzXzxyZxX',
    debug: true // default: false
);
```

When `Debug` is enabled then events are sent to `https://www.google-analytics.com/debug/mp/collect` where issues will be caught with
[GA4Exception](https://github.com/aawnu/php-ga4/blob/master/src/Exception/Ga4Exception.php) (Be aware of `$exception->getPrevious()` stacks);
such response will look as follows:

```json
{
  "validationMessages": [
    {
      "fieldPath": "events",
      "description": "Event at index: [0] has invalid name [_badEventName]. Names must start with an alphabetic character.",
      "validationCode": "NAME_INVALID"
    }
  ]
}
```

Notice: This library already validates that events are properly formatted when added to analytics (`$analytics->addEvent(...)`).

Two important points:

- Events sent to the Validation Server will not show up in reports.
- There is no way for events sent through measurement protocol (Server Side) to show up in the `debugView` in Google Analytics Admin.

## Documentation

- [Measurement Protocol](https://developers.google.com/analytics/devguides/collection/protocol/ga4)
- [Measurement Protocol: Reference](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag)
- [Measurement Protocol: User Properties](https://developers.google.com/analytics/devguides/collection/protocol/ga4/user-properties?client_type=gtag)
- [Measurement Protocol: Events](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/events)
  - [Reserved Event Names](https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference?client_type=gtag#reserved_event_names)
- [Measurement Protocol: Validation](https://developers.google.com/analytics/devguides/collection/protocol/ga4/validating-events?client_type=gtag)
- [Measurement Protocol: User Data](https://developers.google.com/analytics/devguides/collection/ga4/uid-data)
