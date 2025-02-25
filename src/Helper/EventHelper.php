<?php

namespace AlexWestergaard\PhpGa4\Helper;

use AlexWestergaard\PhpGa4\Facade\Type\GtmEventType;
use AlexWestergaard\PhpGa4\Facade\Type\EventType;
use AlexWestergaard\PhpGa4\Facade\Type\DefaultEventParamsType;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;

abstract class EventHelper extends IOHelper implements EventType
{
    protected null|string $language;
    protected null|string $page_location;
    protected null|string $page_referrer;
    protected null|string $page_title;
    protected null|string $screen_resolution;

    protected null|string $session_id;
    protected null|int $engagement_time_msec;

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

    public function setSessionId(string $id)
    {
        $this->session_id = $id;
        return $this;
    }

    public function setEngagementTimeMSec(int $msec)
    {
        $this->engagement_time_msec = $msec;
        return $this;
    }

    public function toArray(): array
    {
        $return = [];

        if (!method_exists($this, 'getName')) {
            throw Ga4EventException::throwNameMissing();
        } else {
            $name = $this->getName();

            if (empty($name)) {
                throw Ga4EventException::throwNameMissing();
            } elseif (strlen($name) > 40) {
                throw Ga4EventException::throwNameTooLong();
            } elseif (preg_match('/[^\w\d\-]|^\-|\-$/', $name)) {
                throw Ga4EventException::throwNameInvalid();
            } elseif (in_array($name, EventType::RESERVED_NAMES) && !($this instanceof GtmEventType)) {
                throw Ga4EventException::throwNameReserved($name);
            } else {
                $return['name'] = $name;
            }
        }

        $return['params'] = parent::toArray();

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
            [
                'language',
                'page_location',
                'page_referrer',
                'page_title',
                'screen_resolution',
            ],
            $this->getParams(),
            $this->getRequiredParams()
        ));
    }

    public static function new(): static
    {
        return new static();
    }

    /** @deprecated 1.1.1 */
    public function debug()
    {
        return $this;
    }
}
