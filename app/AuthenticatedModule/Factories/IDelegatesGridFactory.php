<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\DelegatesGrid;

interface IDelegatesGridFactory
{
    public function create() : DelegatesGrid;
}
