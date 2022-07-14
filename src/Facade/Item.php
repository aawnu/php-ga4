<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface Item
{
    /**
     * The ID of the item. \
     * \* One of item_id or item_name is required.
     *
     * @var item_id
     * @param string $id eg. SKU_12345
     */
    public function setItemId(string $id);

    /**
     * The name of the item. \
     * \* One of item_id or item_name is required.
     *
     * @var item_name
     * @param string $name eg. Stan and Friends Tee
     */
    public function setItemName(string $name);

    /**
     * A product affiliation to designate a supplying company or brick and mortar store location. \
     * Event-level and item-level affiliation parameters are independent.
     *
     * @var affiliation
     * @param string $affiliation eg. Google Store
     */
    public function setAffiliation(string $affiliation);

    /**
     * The coupon name/code associated with the item. \
     * Event-level and item-level coupon parameters are independent.
     *
     * @var coupon
     * @param string $code eg. SUMMER_FUN
     */
    public function setCoupon(string $code);

    /**
     * The currency, in 3-letter ISO 4217 format. \
     * If set, event-level currency is ignored.
     *
     * @link https://en.wikipedia.org/wiki/ISO_4217#Active_codes
     * @var currency
     * @param string $iso eg. USD
     */
    public function setCurrency(string $iso);

    /**
     * The monetary discount value associated with the item.
     *
     * @var discount
     * @param string $amount eg. 2.22
     */
    public function setDiscount($amount);

    /**
     * The index/position of the item in a list.
     *
     * @var index
     * @param string $i eg. 5
     */
    public function setIndex(int $i);

    /**
     * The brand of the item.
     *
     * @var item_brand
     * @param string $brand eg. Google
     */
    public function setItemBrand(string $brand);

    /**
     * The category of the item. If used as part of a category hierarchy or taxonomy then this will be the first category.
     *
     * @var item_category[]
     * @param string $category eg. Apparel
     */
    public function addItemCategory(string $category);

    /**
     * The ID of the list in which the item was presented to the user. \
     * If set, event-level item_list_id is ignored. \
     * If not set, event-level item_list_id is used, if present.
     *
     * @var item_list_id
     * @param string $id eg. related_products
     */
    public function setItemListId(string $id);

    /**
     * The name of the list in which the item was presented to the user. \
     * If set, event-level item_list_id is ignored. \
     * If not set, event-level item_list_id is used, if present.
     *
     * @var item_list_name
     * @param string $name eg. Related products
     */
    public function setItemListName(string $name);

    /**
     * The item variant or unique code or description for additional item details/options.
     *
     * @var item_variant
     * @param string $variant eg. green
     */
    public function setItemVariant(string $variant);

    /**
     * The location associated with the item. It's recommended to use the Google Place ID that corresponds to the associated item. A custom location ID can also be used. \
     * If set, event-level location_id is ignored. \
     * If not set, event-level location_id is used, if present.
     *
     * @link https://developers.google.com/maps/documentation/places/web-service/place-id
     * @var location_id
     * @param string $id eg. L_12345
     */
    public function setLocationId(string $id);

    /**
     * The monetary price of the item, in units of the specified currency parameter.
     *
     * @var price
     * @param string $amount eg. 9.99
     */
    public function setPrice($amount);

    /**
     * Item quantity.
     *
     * @var quantity
     * @param string $amount eg. 1
     */
    public function setQuantity(int $amount);
}
