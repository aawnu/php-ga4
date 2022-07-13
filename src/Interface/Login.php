<?php

namespace AlexWestergaard\PhpGa4\Interface;

interface Login
{
    /**
     * The method used to login.
     *
     * @var method
     * @param string $method eg. Google
     */
    public function setMethod(string $method);
}
