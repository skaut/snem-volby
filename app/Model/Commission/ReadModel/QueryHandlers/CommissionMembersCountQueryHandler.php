<?php

declare(strict_types=1);

namespace Model\Commission\ReadModel\QueryHandlers;

use Model\Commission\ReadModel\Queries\CommissionMembersCountQuery;
use Model\Commission\Repositories\ICommissionMemberRepository;

final class CommissionMembersCountQueryHandler
{
    private ICommissionMemberRepository $commissionMemberRepository;

    public function __construct(ICommissionMemberRepository $commissionMemberRepository)
    {
        $this->commissionMemberRepository = $commissionMemberRepository;
    }

    public function __invoke(CommissionMembersCountQuery $_) : int
    {
        return $this->commissionMemberRepository->getCount();
    }
}
