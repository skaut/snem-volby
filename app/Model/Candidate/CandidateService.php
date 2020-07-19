<?php

declare(strict_types=1);

namespace Model\Candidate;

use eGen\MessageBus\Bus\CommandBus;
use Model\Candidate\Commands\SaveCandidates;

final class CandidateService
{
    private CommandBus $commandBus;

    private int $congressEventId;

    public function __construct(
        int $congressEventId,
        CommandBus $commandBus
    ) {
        $this->congressEventId = $congressEventId;
        $this->commandBus      =$commandBus;
    }

    public function saveCandidates() : void
    {
        $this->commandBus->handle(new SaveCandidates($this->congressEventId));
    }
}
