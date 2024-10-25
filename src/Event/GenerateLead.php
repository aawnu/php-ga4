<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\GA4Exception;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class GenerateLead extends Model\Event implements Facade\GenerateLead
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
        return $this;
    }

    /**
     * @param int|float $val
     */
    public function setValue($val)
    {
        if (!is_numeric($val)) {
            throw new GA4Exception("setValue value must be numeric");
        }

        $this->value = 0 + $val;
        return $this;
    }
}
