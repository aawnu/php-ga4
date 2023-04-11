<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface ViewPromotionFacade extends hasItemsFacade
{
    /**
     * The name of the promotional creative. \
     * Ignored if set at the item-level.
     *
     * @var creative_name
     * @param string $name eg. summer_banner2
     */
    public function setCreativeName(string $name);

    /**
     * The name of the promotional creative slot associated with the event. \
     * Ignored if set at the item-level.
     *
     * @var creative_slot
     * @param string $slot eg. featured_app_1
     */
    public function setCreativeSlot(string $slot);

    /**
     * The ID of the location. \
     * Ignored if set at the item-level.
     *
     * @link https://developers.google.com/maps/documentation/places/web-service/place-id
     * @var location_id
     * @param string $id eg. L_12345
     */
    public function setLocationId(string $id);

    /**
     * The ID of the promotion associated with the event. \
     * Ignored if set at the item-level.
     *
     * @var promotion_id
     * @param string $id eg. P_12345
     */
    public function setPromotionId(string $id);

    /**
     * The name of the promotion associated with the event. \
     * Ignored if set at the item-level.
     *
     * @var promotion_name
     * @param string $name eg. Summer Sale
     */
    public function setPromotionName(string $name);
}
