<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class JoinGroup extends Model\Event implements Facade\JoinGroup
{
    protected $group_id;

    public function getName(): string
    {
        return 'join_group';
    }

    public function getParams(): array
    {
        return [
            'group_id',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setGroupId(string $id)
    {
        $this->group_id = $id;
        return $this;
    }
}
