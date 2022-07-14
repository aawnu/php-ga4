<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class Share extends Model\Event implements Facade\Share
{
    protected $method;
    protected $content_type;
    protected $item_id;

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

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function setContentType(string $type)
    {
        $this->content_type = $type;
        return $this;
    }

    public function setItemId(string $id)
    {
        $this->item_id = $id;
        return $this;
    }
}
