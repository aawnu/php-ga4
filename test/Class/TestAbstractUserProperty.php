<?php

namespace AlexWestergaard\PhpGa4Test\Class;

use AlexWestergaard\PhpGa4\Helper\AbstractUserProperty;

class TestAbstractUserProperty extends AbstractUserProperty
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
