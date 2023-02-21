<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\AbstractEvent;

class TutorialComplete extends AbstractEvent
{

    public function getName(): string
    {
        return 'tutorial_complete';
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
