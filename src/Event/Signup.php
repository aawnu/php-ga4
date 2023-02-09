<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class Signup extends Model\Event implements Facade\SignUp
{
    protected null|string $method;

    public function getName(): string
    {
        return 'sign_up';
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
