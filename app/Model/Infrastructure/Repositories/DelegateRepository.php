<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Model\Delegate\Delegate;
use Model\Delegate\DelegateNotFound;
use Model\Delegate\FirstLoginAlreadyExists;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\DTO\Delegate\SkautisDelegate;
use Throwable;

final class DelegateRepository extends AggregateRepository implements IDelegateRepository
{
    /**
     * @param SkautisDelegate[] $delegates
     *
     * @throws Throwable
     */
    public function saveDelegates(array $delegates) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($delegates) : void {
            foreach ($delegates as $delegate) {
                $this->getEntityManager()->persist(new Delegate(
                    $delegate->getPersonId(),
                    $delegate->getName(),
                    $delegate->getType(),
                    $delegate->getUnitNumber(),
                    $delegate->getUnitName()
                ));
            }
            $this->getEntityManager()->flush();
        });
    }

    /**
     * @return Collection<int, Delegate>
     */
    public function getDelegates() : Collection
    {
        return new ArrayCollection($this->getEntityManager()->getRepository(Delegate::class)->findAll());
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
            ->where('d.votedAt IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getParticipatedCount() : int
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
            throw new FirstLoginAlreadyExists();
        }

        $this->getEntityManager()->persist($delegate);
        $this->getEntityManager()->flush();
    }
}
