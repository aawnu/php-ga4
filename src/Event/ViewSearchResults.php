<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

class ViewSearchResults extends EventHelper implements Facade\Group\ViewSearchResultsFacade
{
    protected null|string $search_term;
    protected array $items = [];

    public function getName(): string
    {
        return 'view_search_results';
    }

    public function getParams(): array
    {
        return [
            'search_term',
            'items',
        ];
    }

    public function getRequiredParams(): array
    {
        return [
            'items',
        ];
    }

    public function setSearchTerm(null|string $term)
    {
        $this->search_term = $term;
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
