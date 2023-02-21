<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\AbstractEvent;
use AlexWestergaard\PhpGa4\Facade;

class GenerateLead extends AbstractEvent implements Facade\Group\GenerateLead
{
    protected null|string $currency;
    protected null|int|float $value;

    public function getName(): string
    {
        return 'generate_lead';
    }

    public function getParams(): array
    {
        return [
            'currency',
            'value',
        ];
    }

    public function getRequiredParams(): array
    {
        $return = [];

        if (
            isset($this->currency) && !isset($this->value)
            || !isset($this->currency) && isset($this->value)
        ) {
            $return = [
                'currency',
                'value'
            ];
        }

        return $return;
    }

    public function setCurrency(null|string $iso)
    {
        $this->currency = $iso;
        return $this;
    }

    public function setValue(null|int|float $val)
    {
        $this->value = $val;
        return $this;
    }
}
