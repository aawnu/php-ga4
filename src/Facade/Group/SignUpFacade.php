<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface SignUpFacade
{
    /**
     * The method used for sign up.
     *
     * @var method
     * @param string $method eg. Google
     */
    public function setMethod(string $method);
}
