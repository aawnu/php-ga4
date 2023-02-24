<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

interface PostScore
{
    /**
     * The score to post.
     *
     * @var score
     * @param string $score eg. 10000
     * @return void
     */
    public function setScore(int $score);

    /**
     * The level for the score.
     *
     * @var level
     * @param string $lvl eg. 5
     * @return void
     */
    public function setLevel(int $lvl);

    /**
     * The character that achieved the score.
     *
     * @var character
     * @param string $char eg. Player 1
     * @return void
     */
    public function setCharacter(string $char);
}
