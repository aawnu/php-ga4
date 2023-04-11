<?php

namespace AlexWestergaard\PhpGa4Test\Mocks;

use AlexWestergaard\PhpGa4\Helper;

class MockIOHelper extends Helper\IOHelper
{
    public $test;
    public $test_required;
    public $test_array = [];

    public function getParams(): array
    {
        return ['test', 'test_array'];
    }

    public function getRequiredParams(): array
    {
        return ['test_required'];
    }

    public function setTest($val)
    {
        $this->test = $val;
    }

    public function setTestRequired($val)
    {
        $this->test_required = $val;
    }

    public function addTestArray($val)
    {
        $this->test_array[] = $val;
    }
};
