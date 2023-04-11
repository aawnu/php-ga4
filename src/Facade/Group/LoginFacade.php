<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface LoginFacade
{
    /**
     * The method used to login.
     *
     * @var method
     * @param string $method eg. Google
     */
    public function setMethod(string $method);
}
