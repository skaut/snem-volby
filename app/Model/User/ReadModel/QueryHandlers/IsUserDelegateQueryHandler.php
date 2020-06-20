<?php

declare(strict_types=1);

namespace Model\User\ReadModel\QueryHandlers;

use Model\Delegate\Repositories\IDelegateRepository;
use Model\User\ReadModel\Queries\IsUserDelegateQuery;

final class IsUserDelegateQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(IsUserDelegateQuery $query) : bool
    {
        return $this->delegateRepository->getDelegate($query->getPersonId()) !== null;
    }
}
