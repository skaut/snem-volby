<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\TipBox;

interface ITipBoxFactory
{
    public function create() : TipBox;
}
