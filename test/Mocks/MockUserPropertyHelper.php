<?php

namespace AlexWestergaard\PhpGa4Test\Mocks;

use AlexWestergaard\PhpGa4\Helper;

class MockUserPropertyHelper extends Helper\UserPropertyHelper
{
    protected string $name;
    protected int|float|string $value;

    public function setValue(int|float|string $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getParams(): array
    {
        return ['name', 'value'];
    }

    public function getRequiredParams(): array
    {
        return ['name', 'value'];
    }
};
