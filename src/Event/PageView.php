<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

/**
 * This is not an official SST Event, please use this with caution
 * 
 * @link https://developers.google.com/analytics/devguides/collection/ga4/views?client_type=gtag
 * @deprecated unofficial-event
 * @internal
 */
class PageView extends EventHelper implements Facade\Type\DefaultEventParamsType
{
    protected null|string $method;

    public function getName(): string
    {
        return 'page_view';
    }

    public function getParams(): array
    {
        return [
            'language',
            'page_location',
            'page_referrer',
            'page_title',
            'screen_resolution',
        ];
    }

    public function getRequiredParams(): array
    {
        return [
            'page_title',
            'page_location',
        ];
    }
}
