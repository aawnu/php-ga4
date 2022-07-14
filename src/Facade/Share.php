<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface Share
{
    /**
     * The method in which the content is shared.
     *
     * @var method
     * @param string $method eg. Twitter
     */
    public function setMethod(string $method);

    /**
     * The type of shared content.
     *
     * @var content_type
     * @param string $type eg. image
     */
    public function setContentType(string $type);

    /**
     * The ID of the shared content.
     *
     * @var item_id
     * @param string $id eg. C_12345
     */
    public function setItemId(string $id);
}
