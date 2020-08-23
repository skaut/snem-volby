<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Model\Delegate\Delegate;
use Model\Delegate\DelegateNotFound;
use Model\Delegate\Repositories\IDelegateRepository;
use stdClass;

final class DelegateRepository extends AggregateRepository implements IDelegateRepository
{
    /**
     * @param stdClass[] $delegates
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveDelegates(array $delegates) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($delegates) : void {
            foreach ($delegates as $delegate) {
                $this->getEntityManager()->persist(new Delegate($delegate->ID_Person));
            }
            $this->getEntityManager()->flush();
        });
    }

    /**
     * @throws DelegateNotFound
     */
    public function getDelegate(int $personId) : Delegate
    {
        $delegate = $this->getEntityManager()->getRepository(Delegate::class)->findOneBy(['personId' => $personId]);

        if ($delegate === null) {
            throw new DelegateNotFound();
        }

        return $delegate;
    }

    public function getCount() : int
    {
        return $this->getEntityManager()->getRepository(Delegate::class)->count([]);
    }

    public function getVotedCount() : int
    {
        return (int) $this->getEntityManager()->createQueryBuilder()->select('count(d)')
            ->from(Delegate::class, 'd')
            ->where('d.firstLoginAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function setDelegateFirstLogin(int $personId) : void
    {
        $delegate = $this->getDelegate($personId);
        if (! $delegate->setFirstLoginAt()) {
            return;
        }

        $this->getEntityManager()->persist($delegate);
        $this->getEntityManager()->flush();
    }
}
