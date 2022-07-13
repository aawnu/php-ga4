<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class SelectContent extends Model\Event implements Interface\SelectContent
{
    protected $content_type;
    protected $item_id = [];

    public function getName(): string
    {
        return 'select_content';
    }

    public function getParams(): array
    {
        return [
            'content_type',
            'item_id',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setContentType(string $type)
    {
        $this->content_type = $type;
    }

    public function setItemId(string $id)
    {
        $this->item_id = $id;
    }
}
