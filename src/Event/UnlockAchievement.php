<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\AbstractEvent;
use AlexWestergaard\PhpGa4\Facade;

class UnlockAchievement extends AbstractEvent implements Facade\Group\UnlockAchievement
{
    protected null|string $achievement_id;

    public function getName(): string
    {
        return 'unlock_achievement';
    }

    public function getParams(): array
    {
        return [
            'achievement_id',
        ];
    }

    public function getRequiredParams(): array
    {
        return [
            'achievement_id',
        ];
    }

    public function setAchievementId(null|string $id)
    {
        $this->achievement_id = $id;
        return $this;
    }
}
