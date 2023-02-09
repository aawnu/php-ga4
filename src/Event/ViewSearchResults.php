<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class ViewSearchResults extends Model\Event implements Facade\ViewSearchResults
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

    public function addItem(Item $item)
    {
        $this->items[] = $item->toArray();
        return $this;
    }
}
