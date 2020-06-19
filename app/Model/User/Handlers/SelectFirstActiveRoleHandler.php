<?php

declare(strict_types=1);

namespace Model\User\Handlers;

use Model\User\Commands\SelectFirstActiveRole;
use Model\User\Exception\UserHasNoRole;
use Model\UserService;
use function count;

final class SelectFirstActiveRoleHandler
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(SelectFirstActiveRole $command) : void
    {
        $roles = $this->userService->getRelatedSkautisRoles();
        if (count($roles) === 0) {
            throw new UserHasNoRole();
        }
        $this->userService->updateSkautISRole($roles[0]->ID);
    }
}
