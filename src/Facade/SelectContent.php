<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface SelectContent
{
    /**
     * The type of selected content.
     *
     * @var content_type
     * @param string $type eg. product
     */
    public function setContentType(string $type);

    /**
     * An identifier for the item that was selected.
     *
     * @var item_id
     * @param string $id eg. I_12345
     */
    public function setItemId(string $id);
}
