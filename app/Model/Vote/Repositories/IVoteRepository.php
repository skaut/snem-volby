<?php

declare(strict_types=1);

namespace Model\Vote\Repositories;

use Model\UsersVote;
use Model\Vote;

interface IVoteRepository
{
    public function saveUserVote(Vote $vote, UsersVote $usersVote) : void;
}
