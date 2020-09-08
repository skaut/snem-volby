<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\ObjectionForm;

interface IObjectionFormFactory
{
    public function create() : ObjectionForm;
}
