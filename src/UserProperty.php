<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class UserProperty extends Model\ToArray implements Interface\Export
{
    public function getParams(): array
    {
        return [];
    }

    public function getRequiredParams(): array
    {
        return [];
    }
    
    public function toArray(bool $isParent = false, $childErrors = null): array
    {
        return parent::toArray($isParent, $childErrors);
    }
}
