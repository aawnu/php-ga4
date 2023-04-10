<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface DefaultEventParams
{
    public function setLanguage(string $lang);
    public function setPageLocation(string $url);
    public function setPageReferrer(string $url);
    public function setPageTitle(string $title);
    public function setScreenResolution(string $wxh);

    public function toArray(): array;
}
