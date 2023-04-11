<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;

class SelectContent extends EventHelper implements Facade\Group\SelectContentFacade
{
    protected null|string $content_type;
    protected null|string $item_id;

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

    public function setContentType(null|string $type)
    {
        $this->content_type = $type;
        return $this;
    }

    public function setItemId(null|string $id)
    {
        $this->item_id = $id;
        return $this;
    }
}
