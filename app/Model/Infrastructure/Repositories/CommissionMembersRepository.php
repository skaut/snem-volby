<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Model\Commission\CommissionMember;
use Model\Commission\CommissionMemberNotFound;
use Model\Commission\Repositories\ICommissionMemberRepository;
use stdClass;

final class CommissionMembersRepository extends AggregateRepository implements ICommissionMemberRepository
{
    /**
     * @param stdClass[] $commissionMembers
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveCommissionMembers(array $commissionMembers) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($commissionMembers) : void {
            foreach ($commissionMembers as $commissionMember) {
                $this->getEntityManager()->persist(new CommissionMember($commissionMember->ID_Person));
            }
            $this->getEntityManager()->flush();
        });
    }

    /**
     * @throws CommissionMemberNotFound
     */
    public function findCommissionMember(int $personId) : CommissionMember
    {
        $commissionMember = $this->getEntityManager()->getRepository(CommissionMember::class)->findOneBy(['personId' => $personId]);

        if ($commissionMember === null) {
            throw new CommissionMemberNotFound();
        }

        return $commissionMember;
    }

    public function getCount() : int
    {
        return $this->getEntityManager()->getRepository(CommissionMember::class)->count([]);
    }
}
