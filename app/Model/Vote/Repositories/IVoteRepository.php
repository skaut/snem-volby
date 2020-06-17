<?php

declare(strict_types=1);

namespace Model\Vote\Repositories;

use Model\Vote\UsersVote;
use Model\Vote\Vote;

interface IVoteRepository
{
    public function saveUserVote(Vote $vote, UsersVote $usersVote) : void;

    public function getUserVote(int $personId) : ?UsersVote;
}
