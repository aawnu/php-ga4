<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Model;
use AlexWestergaard\PhpGa4\Facade;

class PostScore extends Model\Event implements Facade\PostScore
{
    protected null|int $score;
    protected null|int $level;
    protected null|string $character;

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

    public function setScore(null|int $score)
    {
        $this->score = $score;
        return $this;
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
