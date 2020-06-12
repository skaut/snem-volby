<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

class AdminPresenter extends BasePresenter
{
    public function startup() : void
    {
        parent::startup();
        if ($this->userService->isSuperUser()) {
            return;
        }

        $this->flashMessage('Nemáte oprávnění přistupovat ke stránce!', 'danger');
        $this->redirect('Homepage:');
    }
}
