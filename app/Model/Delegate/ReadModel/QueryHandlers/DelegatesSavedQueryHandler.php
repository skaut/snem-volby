<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Model\Delegate\ReadModel\Queries\DelegatesSavedQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class DelegatesSavedQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(DelegatesSavedQuery $_) : bool
    {
        return $this->delegateRepository->getCount() > 0;
    }
}
