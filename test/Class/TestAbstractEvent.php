<?php

namespace AlexWestergaard\PhpGa4Test\Class;

use AlexWestergaard\PhpGa4\Facade\Type\Item;
use AlexWestergaard\PhpGa4\Helper\AbstractEvent;

class TestAbstractEvent extends AbstractEvent
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

    public function addTestItem(Item $val)
    {
        $this->test_items[] = $val->toArray();
    }
};
