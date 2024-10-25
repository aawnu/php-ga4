<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface UnlockAchievement
{
    /**
     * The id of the achievement that was unlocked.
     *
     * @var achievement_id
     * @param string $id eg. A_12345
     */
    public function setAchievementId(string $id);
}
