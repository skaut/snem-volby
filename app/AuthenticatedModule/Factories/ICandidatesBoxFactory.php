<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\CandidatesBox;

interface ICandidatesBoxFactory
{
    public function create() : CandidatesBox;
}
