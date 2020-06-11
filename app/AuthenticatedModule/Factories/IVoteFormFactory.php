<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AccountancyModule\Components\VoteForm;

interface IVoteFormFactory
{
    public function create() : VoteForm;
}
