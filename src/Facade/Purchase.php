<?php

namespace AlexWestergaard\PhpGa4\Facade;

use AlexWestergaard\PhpGa4\Item;

interface Purchase
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
     * The unique identifier of a transaction. \
     * The transaction_id parameter helps you avoid getting duplicate events for a purchase.
     *
     * @var transaction_id
     * @param string $id eg. T_12345
     */
    public function setTransactionId(string $id);

    /**
     * The monetary value of the event.
     *
     * @var value
     * @param integer|float $val eg. 7.77
     */
    public function setValue(int|float $val);

    /**
     * A product affiliation to designate a supplying company or brick and mortar store location. \
     * Event-level and item-level affiliation parameters are independent.
     *
     * @var affiliation
     * @param string $affiliation eg. Google Store
     */
    public function setAffiliation(string $affiliation);

    /**
     * The coupon name/code associated with the event. \
     * Event-level and item-level coupon parameters are independent.
     *
     * @var coupon
     * @param string $code eg. SUMMER_FUN
     */
    public function setCoupon(string $code);

    /**
     * Shipping cost associated with a transaction.
     *
     * @var shipping
     * @param float $cost eg. 3.33
     */
    public function setShipping(float $cost);

    /**
     * Tax cost associated with a transaction.
     *
     * @var tax
     * @param float $tax eg. 1.11
     */
    public function setTax(float $tax);

    /**
     * The items for the event.
     *
     * @var items
     * @param AlexWestergaard\PhpGa4\Module\Item $item
     */
    public function addItem(Item $item);
}
