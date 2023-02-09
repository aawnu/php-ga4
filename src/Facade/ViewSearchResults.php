<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface ViewSearchResults extends hasItems
{
    /**
     * The term that was searched for.
     *
     * @var search_term
     * @param string $term eg. t-shirts
     */
    public function setSearchTerm(string $term);
}
