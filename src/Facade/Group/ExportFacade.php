<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface ExportFacade
{
    /**
     * Convert paramters to array and validate required parameters
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Return array of class properties to be loaded as parameters
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Return array of class properties that MUST be loaded as parameters
     *
     * @return array
     */
    public function getRequiredParams(): array;
}
