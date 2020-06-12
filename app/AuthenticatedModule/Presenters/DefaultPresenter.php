<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use App\AuthenticatedModule\Factories\IVoteFormFactory;
use App\Components\VoteForm;

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
