<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\Components\LoginPanel;

interface ILoginPanelFactory
{
    public function create() : LoginPanel;
}
