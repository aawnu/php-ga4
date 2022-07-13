<?php

namespace AlexWestergaard\PhpGa4\Interface;

interface Search
{
    /**
     * The term that was searched for.
     *
     * @var search_term
     * @param string $term eg. t-shirts
     */
    public function setSearchTerm(string $term);
}
