<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Model\Delegate\ReadModel\Queries\VotedDelegatesCountQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class VotedDelegatesCountQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(VotedDelegatesCountQuery $_) : int
    {
        return $this->delegateRepository->getVotedCount();
    }
}
