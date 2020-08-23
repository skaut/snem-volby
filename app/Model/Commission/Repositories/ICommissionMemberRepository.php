<?php

declare(strict_types=1);

namespace Model\Commission\Repositories;

use Model\Commission\CommissionMember;
use Model\Commission\CommissionMemberNotFound;
use stdClass;

interface ICommissionMemberRepository
{
    /**
     * @param stdClass[] $commissionMembers
     */
    public function saveCommissionMembers(array $commissionMembers) : void;

    /**
     * @throws CommissionMemberNotFound
     */
    public function findCommissionMember(int $personId) : CommissionMember;

    public function getCount() : int;
}
