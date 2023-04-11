<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

class ViewItemList extends EventHelper implements Facade\Group\ViewItemListFacade
{
    protected null|string $item_list_id;
    protected null|string $item_list_name;
    protected array $items = [];

    public function getName(): string
    {
        return 'view_item_list';
    }

    public function getParams(): array
    {
        return [
            'item_list_id',
            'item_list_name',
            'items',
        ];
    }

    public function getRequiredParams(): array
    {
        return ['items'];
    }

    public function setItemListId(null|string $id)
    {
        $this->item_list_id = $id;
        return $this;
    }

    public function setItemListName(null|string $name)
    {
        $this->item_list_name = $name;
        return $this;
    }

    public function addItem(Facade\Type\ItemType $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }

    public function resetItems()
    {
        $this->items = [];
    }
}
