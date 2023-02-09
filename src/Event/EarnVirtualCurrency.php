<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class EarnVirtualCurrency extends Model\Event implements Facade\EarnVirtualCurrency
{
    protected null|string $virtual_currency_name;
    protected null|int|float $value;

    public function getName(): string
    {
        return 'earn_virtual_currency';
    }

    public function getParams(): array
    {
        return [
            'virtual_currency_name',
            'value',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }
    
    public function setVirtualCurrencyName(null|string $name)
    {
        $this->virtual_currency_name = $name;
        return $this;
    }
    
    public function setValue(null|int|float $num)
    {
        $this->value = $num;
        return $this;
    }
}
