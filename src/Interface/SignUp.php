<?php

namespace AlexWestergaard\PhpGa4\Interface;

interface SignUp
{
    /**
     * The method used for sign up.
     *
     * @var method
     * @param string $method eg. Google
     */
    public function setMethod(string $method);
}
