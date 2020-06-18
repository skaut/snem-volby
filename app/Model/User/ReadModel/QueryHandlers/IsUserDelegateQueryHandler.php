<?php

declare(strict_types=1);

namespace Model\User\ReadModel\QueryHandlers;

use Model\User\ReadModel\Queries\IsUserDelegateQuery;
use Model\UserService;

final class IsUserDelegateQueryHandler
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(IsUserDelegateQuery $_) : bool
    {
        return $this->userService->isDelegate();
    }
}
