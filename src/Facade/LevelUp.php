<?php

namespace AlexWestergaard\PhpGa4\Facade;

interface LevelUp
{
    /**
     * The level of the character.
     *
     * @var level
     * @param string $lvl eg. 5
     */
    public function setLevel(int $lvl);

    /**
     * The character that leveled up.
     *
     * @var character
     * @param string $char eg. Player 1
     */
    public function setCharacter(string $char);
}
