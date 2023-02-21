<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface ViewItemList extends hasItems
{
    /**
     * The ID of the list in which the item was presented to the user. \
     * Ignored if set at the item-level.
     *
     * @var item_list_id
     * @param string $id eg. related_products
     */
    public function setItemListId(string $id);
    /**
     * The name of the list in which the item was presented to the user. \
     * Ignored if set at the item-level.
     *
     * @var item_list_name
     * @param string $name eg. related_products
     */
    public function setItemListName(string $name);
}
