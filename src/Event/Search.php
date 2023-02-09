<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class Search extends Model\Event implements Facade\Search
{
    protected null|string $search_term;

    public function getName(): string
    {
        return 'search';
    }

    public function getParams(): array
    {
        return [
            'search_term',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setSearchTerm(null|string $term)
    {
        $this->search_term = $term;
        return $this;
    }
}
