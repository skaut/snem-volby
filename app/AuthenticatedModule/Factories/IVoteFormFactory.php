<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\Components\VoteForm;

interface IVoteFormFactory
{
    public function create() : VoteForm;
}
