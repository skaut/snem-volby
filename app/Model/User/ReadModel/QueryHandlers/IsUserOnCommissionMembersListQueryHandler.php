<?php

declare(strict_types=1);

namespace Model\User\ReadModel\QueryHandlers;

use Model\Commission\CommissionMemberNotFound;
use Model\Commission\Repositories\ICommissionMemberRepository;
use Model\User\ReadModel\Queries\IsUserOnCommissionMembersListQuery;

final class IsUserOnCommissionMembersListQueryHandler
{
    private ICommissionMemberRepository $commissionMemberRepository;

    public function __construct(ICommissionMemberRepository $commissionMemberRepository)
    {
        $this->commissionMemberRepository = $commissionMemberRepository;
    }

    public function __invoke(IsUserOnCommissionMembersListQuery $query) : bool
    {
        try {
            $this->commissionMemberRepository->getCommissionMember($query->getPersonId());

            return true;
        } catch (CommissionMemberNotFound $exc) {
            return false;
        }
    }
}
