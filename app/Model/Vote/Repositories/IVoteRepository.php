<?php

declare(strict_types=1);

namespace Model\Vote\Repositories;

use Model\Delegate\Delegate;
use Model\Vote\Vote;

interface IVoteRepository
{
    public function saveUserVote(Vote $vote, Delegate $usersVote) : void;

    public function getUserVote(int $personId) : ?UsersVote;

    public function getYesVoteCount() : int;

    public function getNoVoteCount() : int;

    public function getAbstainVoteCount() : int;

}
