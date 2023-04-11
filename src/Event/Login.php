<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

class Login extends EventHelper implements Facade\Group\LoginFacade
{
    protected null|string $method;

    public function getName(): string
    {
        return 'login';
    }

    public function getParams(): array
    {
        return [
            'method',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setMethod(null|string $method)
    {
        $this->method = $method;
        return $this;
    }
}
