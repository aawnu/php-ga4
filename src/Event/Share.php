<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\AbstractEvent;
use AlexWestergaard\PhpGa4\Facade;

class Share extends AbstractEvent implements Facade\Group\Share
{
    protected null|string $method;
    protected null|string $content_type;
    protected null|string $item_id;

    public function getName(): string
    {
        return 'share';
    }

    public function getParams(): array
    {
        return [
            'method',
            'content_type',
            'item_id',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setMethod(null|string $method)
    {
        $this->method = $method;
        return $this;
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
