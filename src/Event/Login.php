<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class Login extends Model\Event implements Facade\Login
{
    protected $method;

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

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }
}
