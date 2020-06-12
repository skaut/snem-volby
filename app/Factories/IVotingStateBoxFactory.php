<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\VotingStateBox;

interface IVotingStateBoxFactory
{
    public function create() : VotingStateBox;
}
