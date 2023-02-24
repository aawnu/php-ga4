<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface EarnVirtualCurrency
{
    /**
     * The name of the virtual currency.
     *
     * @var virtual_currency_name
     * @param string $name eg. Gems
     */
    public function setVirtualCurrencyName(string $name);

    /**
     * The value of the virtual currency.
     *
     * @var value
     * @param integer $num eg. 5
     */
    public function setValue(int|float $num);
}
