<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface DefaultEventParams
{
    /** @var language */
    public function setLanguage(string $lang);

    /** @var page_location */
    public function setPageLocation(string $url);

    /** @var page_referrer */
    public function setPageReferrer(string $url);

    /** @var page_title */
    public function setPageTitle(string $title);

    /** @var screen_resolution */
    public function setScreenResolution(string $wxh);

    public function toArray(): array;
}
