<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Components\VotingTimeForm;
use App\AuthenticatedModule\Factories\IVotingTimeFormFactory;
use Model\Delegate\Commands\SaveDelegates;
use Model\Delegate\ReadModel\Queries\DelegatesSavedQuery;

class AdminPresenter extends BasePresenter
{
    private IVotingTimeFormFactory $votingTimeFormFactory;

    public function __construct(IVotingTimeFormFactory $votingTimeFormFactory)
    {
        parent::__construct();
        $this->votingTimeFormFactory = $votingTimeFormFactory;
    }

    public function startup() : void
    {
        parent::startup();
        if ($this->userService->isSuperUser()) {
            $this->template->delegatesSaved = $this->queryBus->handle(new DelegatesSavedQuery());

            return;
        }

        $this->flashMessage('Nemáte oprávnění přistupovat ke stránce!', 'danger');
        $this->redirect('Default:');
    }

    public function handleSaveDelegates() : void
    {
        if (! $this->queryBus->handle(new DelegatesSavedQuery())) {
            $this->commandBus->handle(new SaveDelegates());
        }
        $this->redirect('this');
    }

    public function createComponentVotingTimeForm() : VotingTimeForm
    {
        return $this->votingTimeFormFactory->create();
    }

    public function handleSaveDelegates() : void
    {
        if (! $this->queryBus->handle(new DelegatesSavedQuery())) {
            $this->commandBus->handle(new SaveDelegates());
        }
        $this->redirect('this');
    }
}
