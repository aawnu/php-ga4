<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class LevelUp extends Model\Event implements Facade\LevelUp
{
    protected null|int $level;
    protected null|string $character;

    public function getName(): string
    {
        return 'level_up';
    }

    public function getParams(): array
    {
        return [
            'level',
            'character',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setLevel(null|int $lvl)
    {
        $this->level = $lvl;
        return $this;
    }

    public function setCharacter(null|string $char)
    {
        $this->character = $char;
        return $this;
    }
}
