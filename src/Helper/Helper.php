<?php

namespace AlexWestergaard\PhpGa4\Helper;

class Helper
{
    /** @return array<int,string> */
    public const RESERVED_EVENT_NAMES = [
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

    /** @return array<int,string> */
    public const RESERVED_USER_PROPERTY_NAMES = [
        'first_open_time',
        'first_visit_time',
        'last_deep_link_referrer',
        'user_id',
        'first_open_after_install',
    ];

    /**
     * @param string $input
     * @return string snake_case
     */
    public static function snake(string $input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param string $input
     * @return string CamelCase
     */
    public static function camel(string $input)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
}
