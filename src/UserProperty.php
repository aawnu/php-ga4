<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Helper\AbstractUserProperty;

class UserProperty extends AbstractUserProperty
{
    protected null|string $name;
    protected null|int|float|string $value;

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
}
