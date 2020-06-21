<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Components\VoteForm;
use App\AuthenticatedModule\Factories\IVoteFormFactory;

class DefaultPresenter extends BasePresenter
{
    private IVoteFormFactory $voteFormFactory;

    public function __construct(IVoteFormFactory $voteFormFactory)
    {
        $this->voteFormFactory = $voteFormFactory;
    }

    public function createComponentVoteForm() : VoteForm
    {
        return $this->voteFormFactory->create();
    }
}
