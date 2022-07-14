<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class SpendVirtualCurrency extends Model\Event implements Facade\SpendVirtualCurrency
{
    protected $virtual_currency_name;
    protected $value;
    protected $item_name;

    public function getName(): string
    {
        return 'spend_virtual_currency';
    }

    public function getParams(): array
    {
        return [
            'virtual_currency_name',
            'value',
            'item_name',
        ];
    }

    public function getRequiredParams(): array
    {
        return [
            'virtual_currency_name',
            'value',
        ];
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

    public function setItemName(int $name)
    {
        $this->item_name = $name;
        return $this;
    }
}
