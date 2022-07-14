<?php

namespace AlexWestergaard\PhpGa4\Facade;

use AlexWestergaard\PhpGa4\Item;

interface ViewSearchResults
{
    /**
     * The term that was searched for.
     *
     * @var search_term
     * @param string $term eg. t-shirts
     */
    public function setSearchTerm(string $term);

    /**
     * The items for the event.
     *
     * @var items
     * @param AlexWestergaard\PhpGa4\Item $item
     */
    public function addItem(Item $item);
}
