<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface PageViewFacade
{
    /**
     * The title of the page.
     *
     * @var page_title
     * @param string $title eg. "Page - Example"
     */
    public function setPageTitle(null|string $title);

    /**
     * The url of the page.
     *
     * @var page_location
     * @param string $url eg. https://www.example.com/page?query=value
     */
    public function setPageLocation(null|string $url);
}
