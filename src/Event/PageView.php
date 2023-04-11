<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

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
