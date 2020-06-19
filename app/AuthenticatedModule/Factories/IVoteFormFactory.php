<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\VoteForm;

interface IVoteFormFactory
{
    public function create() : VoteForm;
}
