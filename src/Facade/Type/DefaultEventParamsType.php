<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface DefaultEventParamsType
{
    /**
     * The language of the website
     *
     * @var language
     * @param string $method eg. en-US
     */
    public function setLanguage(string $lang);

    /**
     * Current url of the website
     *
     * @var page_location
     * @param string $url eg. http://mysite.com
     */
    public function setPageLocation(string $url);

    /**
     * Current url of the website
     *
     * @var page_referrer
     * @param string $url eg. http://searchengine.com/?q=mysite.com
     */
    public function setPageReferrer(string $url);

    /**
     * Current url of the website
     *
     * @var page_title
     * @param string $title eg. "My website"
     */
    public function setPageTitle(string $title);

    /**
     * Current url of the website
     *
     * @var screen_resolution
     * @param string $title eg. "1920x1080" for 1920px width and 1080px height
     */
    public function setScreenResolution(string $wxh);

    public function toArray(): array;
}
