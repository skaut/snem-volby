<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use Model\Delegate\Commands\SaveDelegates;
use Model\Delegate\ReadModel\Queries\DelegatesSavedQuery;

class AdminPresenter extends BasePresenter
{
    public function startup() : void
    {
        parent::startup();
        if ($this->userService->isSuperUser()) {
            $this->template->delegatesSaved = $this->queryBus->handle(new DelegatesSavedQuery());

            return;
        }

        $this->flashMessage('Nemáte oprávnění přistupovat ke stránce!', 'danger');
        $this->redirect('Homepage:');
    }

    public function handleSaveDelegates() : void
    {
        if (! $this->queryBus->handle(new DelegatesSavedQuery())) {
            $this->commandBus->handle(new SaveDelegates());
        }
        $this->redirect('this');
    }
}
