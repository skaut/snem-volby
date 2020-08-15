<?php

declare(strict_types=1);

namespace Model\Commission\Handlers;

use Model\Commission\Commands\SaveCommissionMembers;
use Model\Commission\Repositories\ICommissionMemberRepository;
use Model\UserService;

final class SaveCommissionMembersHandler
{
    private ICommissionMemberRepository $commissionMemberRepository;

    private UserService $userService;

    public function __construct(ICommissionMemberRepository $commissionMemberRepository, UserService $userService)
    {
        $this->commissionMemberRepository = $commissionMemberRepository;
        $this->userService                = $userService;
    }

    public function __invoke(SaveCommissionMembers $command) : void
    {
        $this->commissionMemberRepository->saveCommissionMembers($this->userService->getCommissionMembers());
    }
}
