<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\DefaultEventParamsType;

abstract class EventHelper extends EventMainHelper implements DefaultEventParamsType
{
    protected null|string $language;
    protected null|string $page_location;
    protected null|string $page_referrer;
    protected null|string $page_title;
    protected null|string $screen_resolution;

    protected array $campaign = [];

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

    public function setEventPage(DefaultEventParamsType $page)
    {
        $args = $page->toArray();

        $this->language = $args['language'] ?? null;
        $this->page_location = $args['page_location'] ?? null;
        $this->page_referrer = $args['page_referrer'] ?? null;
        $this->page_title = $args['page_title'] ?? null;
        $this->screen_resolution = $args['screen_resolution'] ?? null;

        return $this;
    }

    public function toArray(): array
    {
        $return = parent::toArray();

        if (!empty($this->campaign)) {
            $return['params'] = array_replace(
                $return['params'],
                $this->campaign
            );
        }

        return $return;
    }

    public function getAllParams(): array
    {
        return array_unique(array_merge(
            parent::getAllParams(),
            [
                'language',
                'page_location',
                'page_referrer',
                'page_title',
                'screen_resolution',
            ],
            $this->getParams(),
            $this->getRequiredParams(),
        ));
    }

    public static function new(): static
    {
        return new static();
    }
}
