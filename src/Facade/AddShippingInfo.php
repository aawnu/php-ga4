<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface AddShippingInfo extends hasItems
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
    public function setValue(int|float $val);

    /**
     * The coupon name/code associated with the event. \
     * Event-level and item-level coupon parameters are independent.
     *
     * @var coupon
     * @param string $code eg. SUMMER_FUN
     */
    public function setCoupon(string $code);

    /**
     * The shipping tier (e.g. Ground, Air, Next-day) selected for delivery of the purchased item.
     *
     * @var shipping_tier
     * @param string $tier eg. Ground
     */
    public function setShippingTier(string $tier);
}
