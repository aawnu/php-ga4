<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class GenerateLead extends Model\Event implements Interface\GenerateLead
{
    protected $currency;
    protected $value;

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

    public function setCurrency(string $iso)
    {
        $this->currency = $iso;
    }

    public function setValue(int|float $val)
    {
        $this->value = $val;
    }
}