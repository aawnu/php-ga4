<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventMainHelper;
use AlexWestergaard\PhpGa4\Facade;

class PageView extends EventMainHelper implements Facade\Group\PageViewFacade
{
    protected null|string $page_title;
    protected null|string $page_location;

    public function getName(): string
    {
        return 'page_view';
    }

    public function getParams(): array
    {
        return [
            'page_title',
            'page_location',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setPageTitle(null|string $title)
    {
        $this->page_title = $title;
        return $this;
    }

    public function setPageLocation(null|string $url)
    {
        $this->page_location = $url;
        return $this;
    }
}
