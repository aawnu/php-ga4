<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;

class TutorialBegin extends Model\Event
{

    public function getName(): string
    {
        return 'tutorial_begin';
    }

    public function getParams(): array
    {
        return [];
    }

    public function getRequiredParams(): array
    {
        return [];
    }
}
