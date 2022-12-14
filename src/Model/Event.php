<?php

namespace AlexWestergaard\PhpGa4\Model;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\GA4Exception;

abstract class Event extends ToArray implements Facade\Export
{
    private bool $debug = false;

    /**
     * Return the name of the event
     *
     * @return string
     */
    abstract public function getName(): string;

    public function debug(bool $on = true)
    {
        $this->debug = true;
        
        return $this;
    }

    /**
     * @param GA4Exception $childErrors
     */
    public function toArray(bool $isParent = false): array
    {
        $return = [];

        if (!method_exists($this, 'getName')) {
            GA4Exception::push("'self::getName()' does not exist");
        } else {
            $name = $this->getName();
            if (empty($name)) {
                GA4Exception::push("Name '{$name}' can not be empty");
            } elseif (strlen($name) > 40) {
                GA4Exception::push("Name '{$name}' can not be longer than 40 characters");
            } elseif (preg_match('/[^\w\d\-]/', $name)) {
                GA4Exception::push("Name '{$name}' can only be letters, numbers, underscores and dashes");
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
                GA4Exception::push("Name '{$name}' is reserved");
            } else {
                $return['name'] = $name;
            }
        }

        $parent = parent::toArray(true);

        if ($this->debug) {
            $parent['debug_mode'] = true;
        }

        if (!$isParent && GA4Exception::hasStack()) {
            throw GA4Exception::getFinalStack();
        }

        if (!empty($parent)) {
            $return['params'] = $parent;
        }

        return $return;
    }

    public static function fromArray(array $params = [])
    {
        $event = static::new();

        $insertables = array_unique(array_merge($event->getParams(), $event->getRequiredParams()));

        foreach ($insertables as $insertable) {
            if (!in_array($insertable, array_keys($params)) || is_null($param = $params[$insertable])) {
                continue;
            }

            $callableName = implode('', array_map('ucfirst', explode('_', $insertable)));

            if (is_array($param)) {
                $callableName = substr($callableName, -1) === 's' ? substr($callableName, 0, -1) : $callableName;
                foreach ($param as $paramRow) {
                    if (method_exists($event, ($method = 'add' . $callableName))) {
                        $event->$method($paramRow);
                    } elseif (method_exists($event, ($method = 'set' . $callableName))) {
                        $event->$method($paramRow);
                    }
                }
            } else {
                if (method_exists($event, ($method = 'add' . $callableName))) {
                    $event->$method($param);
                } elseif (method_exists($event, ($method = 'set' . $callableName))) {
                    $event->$method($param);
                }
            }
        }
        return $event;
    }

    public static function new()
    {
        return new static();
    }
}
