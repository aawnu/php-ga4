<?php

namespace AlexWestergaard\PhpGa4Test\Class;

use PHPUnit\Framework\TestCase as FrameworkTestCase;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;

class TestCase extends FrameworkTestCase
{
    protected function setUp(): void
    {
        Ga4Exception::resetStack();
        parent::setUp();
    }
}