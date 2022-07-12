<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Interface;
use AlexWestergaard\PhpGa4\Model;

class PostScore extends Model\Event implements Interface\PostScore
{
    protected $score;
    protected $level;
    protected $character;

    public function getName(): string
    {
        return 'post_score';
    }

    public function getParams(): array
    {
        return [
            'score',
            'level',
            'character',
        ];
    }

    public function getRequiredParams(): array
    {
        return ['score'];
    }

    public function setScore(int $score)
    {
        $this->level = $score;
    }

    public function setLevel(int $lvl)
    {
        $this->level = $lvl;
    }

    public function setCharacter(string $char)
    {
        $this->character = $char;
    }
}
