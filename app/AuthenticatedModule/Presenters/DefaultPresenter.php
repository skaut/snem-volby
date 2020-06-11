<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AccountancyModule\Components\VoteForm;
use App\AuthenticatedModule\Factories\IVoteFormFactory;

class DefaultPresenter extends BasePresenter
{
    /** @var IVoteFormFactory */
    private $voteFormFactory;

    public function __construct(IVoteFormFactory $voteFormFactory)
    {
        $this->voteFormFactory = $voteFormFactory;
    }

    public function createComponentVoteForm() : VoteForm
    {
        return $this->voteFormFactory->create();
    }
}
