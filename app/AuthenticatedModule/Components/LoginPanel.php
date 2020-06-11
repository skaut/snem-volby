<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use eGen\MessageBus\Bus\QueryBus;
use Model\User\ReadModel\Queries\ActiveSkautisRoleQuery;
use Model\UserService;
use Nette\Security\Identity;
use Nette\Security\User;
use function assert;

final class LoginPanel extends BaseControl
{
    private UserService $userService;

    private User $user;

    private QueryBus $queryBus;

    public function __construct(UserService $userService, User $user, QueryBus $queryBus)
    {
        $this->userService = $userService;
        $this->user        = $user;
        $this->queryBus    = $queryBus;
    }

    public function handleChangeRole(int $roleId) : void
    {
        $this->userService->updateSkautISRole($roleId);

        $identity = $this->user->getIdentity();

        assert($identity instanceof Identity);

        $identity->currentRole = $this->queryBus->handle(new ActiveSkautisRoleQuery());

        $this->redirect('this');
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/LoginPanel.latte');
        if ($this->user->isLoggedIn()) {
            $roles = [];

            foreach ($this->userService->getAllSkautisRoles() as $role) {
                $roles[$role->ID] = isset($role->RegistrationNumber) ? $role->RegistrationNumber . ' - ' . $role->Role : $role->Role;
            }

            $this->template->setParameters([
                'roles' => $roles,
                'currentRoleId' => $this->userService->getRoleId(),
            ]);
        }

        $this->template->render();
    }
}
