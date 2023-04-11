<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

class SpendVirtualCurrency extends EventHelper implements Facade\Group\SpendVirtualCurrencyFacade
{
    protected null|string $virtual_currency_name;
    protected null|int|float $value;
    protected null|string $item_name;

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

    public function setItemName(null|string $name)
    {
        $this->item_name = $name;
        return $this;
    }
}
