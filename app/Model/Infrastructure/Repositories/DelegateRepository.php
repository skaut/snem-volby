<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Model\Delegate\Delegate;
use Model\Delegate\Repositories\IDelegateRepository;

final class DelegateRepository extends AggregateRepository implements IDelegateRepository
{
    public function saveDelegate(Delegate $delegate) : void
    {
        $this->getEntityManager()->persist($delegate);
        $this->getEntityManager()->flush($delegate);
    }

    public function getDelegate(int $personId) : ?Delegate
    {
        return $this->getEntityManager()->getRepository(Delegate::class)->findOneBy(['personId' => $personId]);
    }

    public function getCount() : int
    {
        return $this->getEntityManager()->getRepository(Delegate::class)->count([]);
    }
}
