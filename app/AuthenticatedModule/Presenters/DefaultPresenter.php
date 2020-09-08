<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Components\ObjectionForm;
use App\AuthenticatedModule\Components\VoteForm;
use App\AuthenticatedModule\Factories\IObjectionFormFactory;
use App\AuthenticatedModule\Factories\IVoteFormFactory;
use Model\Objection\ReadModel\Queries\ObjectionsQuery;

class DefaultPresenter extends BasePresenter
{
    private IVoteFormFactory $voteFormFactory;
    private IObjectionFormFactory $objectionFormFactory;

    public function __construct(IVoteFormFactory $voteFormFactory, IObjectionFormFactory $objectionFormFactory)
    {
        $this->voteFormFactory      = $voteFormFactory;
        $this->objectionFormFactory = $objectionFormFactory;
    }

    public function startup() : void
    {
        parent::startup();

        $this->template->setParameters([
            'isUserRSRJ' => $this->userService->isRSRJ(),
            'isUserAdmin' => $this->userService->isAdmin(),
        ]);

        if ($this->userService->isAdmin() || $this->userService->isRSRJ()) {
            $this->template->setParameters([
                'objections' => $this->queryBus->handle(new ObjectionsQuery()),
            ]);

            return;
        }
    }

    public function createComponentVoteForm() : VoteForm
    {
        return $this->voteFormFactory->create();
    }

    public function createComponentObjectionForm() : ObjectionForm
    {
        return $this->objectionFormFactory->create();
    }
}
