<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\VotingTimeForm;

interface IVotingTimeFormFactory
{
    public function create() : VotingTimeForm;
}
