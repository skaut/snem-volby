<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Doctrine\Common\Collections\Collection;
use Model\Delegate\Delegate;
use Model\Delegate\ReadModel\Queries\DelegatesListQuery;
use Model\Delegate\Repositories\IDelegateRepository;

final class DelegatesListQueryHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    /**
     * @return Collection<int, Delegate>
     */
    public function __invoke(DelegatesListQuery $_) : Collection
    {
        return $this->delegateRepository->getDelegates();
    }
}
