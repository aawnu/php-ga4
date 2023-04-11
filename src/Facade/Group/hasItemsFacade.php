<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

use AlexWestergaard\PhpGa4\Facade\Type;

interface hasItemsFacade
{
    /**
     * The items for the event.
     *
     * @var items
     * @param AlexWestergaard\PhpGa4\Item $item
     */
    public function addItem(Type\ItemType $item);

    /**
     * Reset all items for the event.
     *
     * @var items
     */
    public function resetItems();
}
