<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class UnlockAchievement extends Model\Event implements Interface\UnlockAchievement
{
    protected $achievement_id;

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

    public function setAchievementId(string $id)
    {
        $this->achievement_id = $id;
        return $this;
    }
}
