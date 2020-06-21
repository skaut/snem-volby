<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Model\Delegate\ReadModel\Queries\DelegatesCountQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class DelegatesCountQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(DelegatesCountQuery $_) : int
    {
        return $this->delegateRepository->getCount();
    }
}
