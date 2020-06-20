<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Factories\IVotingTimeFormFactory;
use App\AuthenticatedModule\Factories\IPublishResultFactory;
use App\AuthenticatedModule\Factories\IVotingResultFactory;


class AdminPresenter extends BasePresenter
{
    private IVotingResultFactory $votingResultFactory;
    private IPublishResultFactory $publishResultFactory;
    private IVotingTimeFormFactory $votingTimeFormFactory;

    public function __construct(
        IVotingResultFactory $votingResultFactory,
        IPublishResultFactory $publishResultFactory,
        IVotingTimeFormFactory $votingTimeFormFactory
    ) {
        parent::__construct();
        $this->votingResultFactory  = $votingResultFactory;
        $this->publishResultFactory = $publishResultFactory;
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
        $this->redirect(':Homepage:');
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

    protected function createComponentVotingResult() : VotingResult
    {
        return $this->votingResultFactory->create();
    }

    protected function createComponentPublishResult() : PublishResult
    {
        return $this->publishResultFactory->create();
    }
}
