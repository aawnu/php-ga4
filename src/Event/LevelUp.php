<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Model;

class LevelUp extends Model\Event implements Facade\LevelUp
{
    protected $level;
    protected $character;

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

    public function setLevel(int $lvl)
    {
        $this->level = $lvl;
        return $this;
    }

    public function setCharacter(string $char)
    {
        $this->character = $char;
        return $this;
    }
}
