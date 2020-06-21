<?php

declare(strict_types=1);

namespace Model\User\ReadModel\QueryHandlers;

use Model\Delegate\Repositories\IDelegateRepository;
use Model\User\ReadModel\Queries\IsUserOnDelegateListQuery;

final class IsUserOnDelegateListQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(IsUserOnDelegateListQuery $query) : bool
    {
        return $this->delegateRepository->getDelegate($query->getPersonId()) !== null;
    }
}
