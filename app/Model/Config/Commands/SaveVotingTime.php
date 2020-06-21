<?php

declare(strict_types=1);

namespace Model\Config\Commands;

use Model\Config\Handlers\SaveVotingTimeHandler;
use Model\Vote\VotingTime;

/**
 * @see SaveVotingTimeHandler
 */
final class SaveVotingTime
{
    public VotingTime $votingTime;

    public function __construct(VotingTime $votingTime)
    {
        $this->votingTime = $votingTime;
    }

    public function getVotingTime() : VotingTime
    {
        return $this->votingTime;
    }
}
