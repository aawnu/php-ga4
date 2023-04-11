<?php

namespace AlexWestergaard\PhpGa4Test\Mocks;

use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Facade;

class MockEventHelper extends Helper\EventHelper
{
    public $test;
    public $test_required;
    public $test_items = [];

    public function getName(): string
    {
        return 'test';
    }

    public function getParams(): array
    {
        return ['test', 'test_items'];
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

    public function addTestItem(Facade\Type\ItemType $val)
    {
        $this->test_items[] = $val->toArray();
    }
};
