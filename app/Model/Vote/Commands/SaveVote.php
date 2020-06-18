<?php

declare(strict_types=1);

namespace Model\Vote\Commands;

use Model\Vote\Choice;

/**
 * @see SaveVoteHandler
 */
final class SaveVote
{
    public Choice $vote;

    public function __construct(Choice $vote)
    {
        $this->vote = $vote;
    }

    public function getChoice() : Choice
    {
        return $this->vote;
    }
}
