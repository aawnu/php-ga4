<?php

namespace AlexWestergaard\PhpGa4\Facade;

use AlexWestergaard\PhpGa4\Item;

interface RemoveFromCart
{
    /**
     * Currency of the items associated with the event, in 3-letter ISO 4217 format. \
     * \* If you set value then currency is required for revenue metrics to be computed accurately.
     *
     * @link ISO-Codes https://en.wikipedia.org/wiki/ISO_4217#Active_codes
     * @var currency
     * @param string $iso eg. USD
     */
    public function setCurrency(string $iso);

    /**
     * The monetary value of the event.
     *
     * @var value
     * @param integer|float $val eg. 7.77
     */
    public function setValue($val);

    /**
     * The items for the event.
     *
     * @var items
     * @param AlexWestergaard\PhpGa4\Module\Item $item
     */
    public function addItem(Item $item);
}
