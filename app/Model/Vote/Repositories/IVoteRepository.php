<?php

declare(strict_types=1);

namespace Model\Vote\Repositories;

use Model\Delegate\Delegate;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Vote;
use Model\Vote\VotingResult;

interface IVoteRepository
{
    public function saveUserVote(Vote $vote, Delegate $usersVote) : void;

    public function getAllVotesCount() : int;

    public function getVotingResult(IDelegateRepository $delegateRepository) : VotingResult;
}
