<?php

declare(strict_types=1);

namespace Model\Vote\Repositories;

use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Vote;
use Model\Vote\VotingResult;

interface IVoteRepository
{
    public function saveUserVote(int $personId, Vote $vote) : void;

    public function getAllVotesCount() : int;

    public function getVotingResult(IDelegateRepository $delegateRepository) : VotingResult;
}
