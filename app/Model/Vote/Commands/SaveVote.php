<?php

declare(strict_types=1);

namespace Model\Commands\Vote;

use Model\Cashbook\Handlers\Cashbook\SaveVoteHandler;
use Model\Vote\Option;

/**
 * @see SaveVoteHandler
 */
final class SaveVote
{
    public Option $vote;

    public function __construct(Option $vote)
    {
        $this->vote = $vote;
    }

    public function getOption() : Option
    {
        return $this->vote;
    }
}
