<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Helper;

/**
 * UserProperty allows you to add session/client properties that is not related to individual events
 * such as if they are a premium member or from a certain country perhaps.
 */
class UserProperty extends Helper\UserPropertyHelper
{
    protected null|string $name;
    protected null|int|float|string $value;

    /**
     * Set the name of the UserProperty
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): static
    {
        return parent::setName($name);
    }

    /**
     * Set the value of the UserProperty
     *
     * @param int|float|string $value
     *
     * @return static
     */
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
