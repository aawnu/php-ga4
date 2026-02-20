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
        protected null|string $screen_resolution = null,
        protected null|string $session_id = null,
        protected null|int $engagement_time_msec = null,
        protected null|int $event_timestamp_micros = null,
        protected array $campaign = [],
    ) {}

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

    public function setEventTimestampMicros(int|float $microOrUnix)
    {
        $this->event_timestamp_micros = ConvertHelper::timeAsMicro($microOrUnix);
        return $this;
    }

    public function setCampaignSource(string $source)
    {
        $this->campaign['campaign_source'] = $source;
        return $this;
    }

    public function setCampaignMedium(string $medium)
    {
        $this->campaign['campaign_medium'] = $medium;
        return $this;
    }

    public function setCampaignName(string $name)
    {
        $this->campaign['campaign_name'] = $name;
        return $this;
    }

    public function setCampaignTerm(string $term)
    {
        $this->campaign['campaign_term'] = $term;
        return $this;
    }

    public function setCampaignContent(string $content)
    {
        $this->campaign['campaign_content'] = $content;
        return $this;
    }

    public function setCampaignId(string $id)
    {
        $this->campaign['campaign_id'] = $id;
        return $this;
    }

    public function toArray(): array
    {
        return array_replace([
            'language' => $this->language,
            'page_location' => $this->page_location,
            'page_referrer' => $this->page_referrer,
            'page_title' => $this->page_title,
            'screen_resolution' => $this->screen_resolution,
            'session_id' => $this->session_id,
            'engagement_time_msec' => $this->engagement_time_msec,
            'timestamp_micros' => $this->event_timestamp_micros,
        ], $this->campaign);
    }
}
