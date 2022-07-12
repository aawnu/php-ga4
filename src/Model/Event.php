<?php

namespace AlexWestergaard\PhpGa4\Model;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Interface;

abstract class Event extends ToArray implements Interface\Export
{
    /**
     * Return the name of the event
     *
     * @return string
     */
    abstract public function getName(): string;

    abstract public function getParams(): array;

    abstract public function getRequiredParams(): array;

    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        $return = [];
        $errorStack = null;

        if (!method_exists($this, 'getName')) {
            $errorStack = new GA4Exception("'self::getName()' does not exist", $errorStack);
        } else {
            $name = $this->getName();
            if (empty($name)) {
                $errorStack = new GA4Exception("Name '{$name}' can not be empty", $errorStack);
            } elseif (strlen($name) > 40) {
                $errorStack = new GA4Exception("Name '{$name}' can not be longer than 40 characters", $errorStack);
            } elseif (preg_match('/[^\w\d\-]/', $name)) {
                $errorStack = new GA4Exception("Name '{$name}' can only be letters, numbers, underscores and dashes", $errorStack);
            } elseif (in_array($name, [
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
            ])) {
                $errorStack = new GA4Exception("Name '{$name}' is reserved", $errorStack);
            } else {
                $return['name'] = $name;
            }
        }

        $catch = parent::toArray(true, $errorStack);
        $errorStack = $catch['error'];

        if (is_array($catch['data']) && !empty($catch['data'])) {
            $return['params'] = $catch['data'];
        }

        if ($errorStack instanceof GA4Exception) {
            throw $errorStack;
        }

        return $return;
    }
}
