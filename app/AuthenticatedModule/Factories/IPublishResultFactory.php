<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Factories;

use App\AuthenticatedModule\Components\PublishResult;

interface IPublishResultFactory
{
    public function create() : PublishResult;
}
