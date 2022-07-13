<?php

namespace AlexWestergaard\PhpGa4\Interface;

interface JoinGroup
{
    /**
     * The ID of the group.
     *
     * @var group_id
     * @param string $id eg. G_12345
     */
    public function setGroupId(string $id);
}
