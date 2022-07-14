<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class ViewPromotion extends Model\Event implements Interface\ViewPromotion
{
    protected $creative_name;
    protected $creative_slot;
    protected $location_id;
    protected $promotion_id;
    protected $promotion_name;
    protected $items = [];

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

    public function setCreativeName(string $name)
    {
        $this->creative_name = $name;
        return $this;
    }

    public function setCreativeSlot(string $slot)
    {
        $this->creative_slot = $slot;
        return $this;
    }

    public function setLocationId(string $id)
    {
        $this->location_id = $id;
        return $this;
    }

    public function setPromotionId(string $id)
    {
        $this->promotion_id = $id;
        return $this;
    }

    public function setPromotionName(string $name)
    {
        $this->promotion_name = $name;
        return $this;
    }

    public function addItem(Item $item)
    {
        $this->items = $item->toArray();
        return $this;
    }
}
