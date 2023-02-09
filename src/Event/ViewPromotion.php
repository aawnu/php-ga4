<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class ViewPromotion extends Model\Event implements Facade\ViewPromotion
{
    protected null|string $creative_name;
    protected null|string $creative_slot;
    protected null|string $location_id;
    protected null|string $promotion_id;
    protected null|string $promotion_name;
    protected array $items = [];

    public function getName(): string
    {
        return 'view_promotion';
    }

    public function getParams(): array
    {
        return [
            'creative_name',
            'creative_slot',
            'location_id',
            'promotion_id',
            'promotion_name',
            'items',
        ];
    }

    public function getRequiredParams(): array
    {
        return ['items'];
    }

    public function setCreativeName(null|string $name)
    {
        $this->creative_name = $name;
        return $this;
    }

    public function setCreativeSlot(null|string $slot)
    {
        $this->creative_slot = $slot;
        return $this;
    }

    public function setLocationId(null|string $id)
    {
        $this->location_id = $id;
        return $this;
    }

    public function setPromotionId(null|string $id)
    {
        $this->promotion_id = $id;
        return $this;
    }

    public function setPromotionName(null|string $name)
    {
        $this->promotion_name = $name;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
