<?php

namespace AlexWestergaard\PhpGa4\Facade;

use AlexWestergaard\PhpGa4\Item;

interface SelectItem
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

    /**
     * The items for the event. \
     * \* The items array is expected to have a single element, representing the selected item. If multiple elements are provided, only the first element in items will be used.
     *
     * @var items
     * @param AlexWestergaard\PhpGa4\Item $item
     */
    public function setItem(Item $item);
}
