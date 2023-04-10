<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface Event extends IO
{
    /** @return array<int,string> */
    public const RESERVED_NAMES = [
        'ad_activeview',
        'ad_click',
        'ad_exposure',
        'ad_impression',
        'ad_query',
        'adunit_exposure',
        'app_clear_data',
        'app_install',
        'app_update',
        'app_remove',
        'error',
        'first_open',
        'first_visit',
        'in_app_purchase',
        'notification_dismiss',
        'notification_foreground',
        'notification_open',
        'notification_receive',
        'os_update',
        'screen_view',
        'session_start',
        'user_engagement',
    ];

    /**
     * Return NAME of Event
     *
     * @return string snake_case
     */
    public function getName(): string;

    public function setLanguage(string $lang);
    public function setPageLocation(string $url);
    public function setPageReferrer(string $url);
    public function setPageTitle(string $title);
    public function setScreenResolution(string $wxh);
}
