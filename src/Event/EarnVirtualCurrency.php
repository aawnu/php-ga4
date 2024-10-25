<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class EarnVirtualCurrency extends Model\Event implements Facade\EarnVirtualCurrency
{
    protected $virtual_currency_name;
    protected $value;

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
    
    public function setVirtualCurrencyName(string $name)
    {
        $this->virtual_currency_name = $name;
        return $this;
    }
    
    public function setValue(int $num)
    {
        $this->value = $num;
        return $this;
    }
}
