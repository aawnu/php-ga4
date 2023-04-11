<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\DefaultEventParamsType;

class EventParamsHelper implements DefaultEventParamsType
{
    public function __construct(
        protected null|string $language = null,
        protected null|string $page_location = null,
        protected null|string $page_referrer = null,
        protected null|string $page_title = null,
        protected null|string $screen_resolution = null
    ) {
    }

    public function setLanguage(string $lang)
    {
        $this->language = $lang;
        return $this;
    }

    public function setPageLocation(string $url)
    {
        $this->page_location = $url;
        return $this;
    }

    public function setPageReferrer(string $url)
    {
        $this->page_referrer = $url;
        return $this;
    }

    public function setPageTitle(string $title)
    {
        $this->page_title = $title;
        return $this;
    }

    public function setScreenResolution(string $wxh)
    {
        $this->screen_resolution = $wxh;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'language' => $this->language,
            'page_location' => $this->page_location,
            'page_referrer' => $this->page_referrer,
            'page_title' => $this->page_title,
            'screen_resolution' => $this->screen_resolution,
        ];
    }
}
