<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Item;

class SelectPromotion extends Model\Event implements Interface\SelectPromotion
{
    protected $creative_name;
    protected $creative_slot;
    protected $location_id;
    protected $promotion_id;
    protected $promotion_name;
    protected $items;

    public function getName(): string
    {
        return 'select_promotion';
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
    }

    public function setCreativeSlot(string $slot)
    {
        $this->creative_slot = $slot;
    }

    public function setLocationId(string $id)
    {
        $this->location_id = $id;
    }

    public function setPromotionId(string $id)
    {
        $this->promotion_id = $id;
    }

    public function setPromotionName(string $name)
    {
        $this->promotion_name = $name;
    }

    public function addItem(Item $item)
    {
        $this->items = $item->toArray();
    }
}
