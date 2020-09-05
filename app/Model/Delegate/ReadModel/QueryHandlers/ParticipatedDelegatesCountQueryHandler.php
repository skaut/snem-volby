<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Model\Delegate\ReadModel\Queries\ParticipatedDelegatesCountQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class ParticipatedDelegatesCountQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(ParticipatedDelegatesCountQuery $_) : int
    {
        return $this->delegateRepository->getParticipatedCount();
    }
}
