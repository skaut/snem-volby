<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use DateTimeImmutable;
use Model\Delegate\ReadModel\Queries\DelegateVoteTimeQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class DelegateVoteTimeQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(DelegateVoteTimeQuery $query) : ?DateTimeImmutable
    {
        $delegate = $this->delegateRepository->getDelegate($query->getPersonId());

        return $delegate !== null ? $delegate->getVotedAt() : null;
    }
}
