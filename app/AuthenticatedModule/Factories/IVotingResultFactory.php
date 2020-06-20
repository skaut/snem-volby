<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\VotingResult;

interface IVotingResultFactory
{
    public function create() : VotingResult;
}
