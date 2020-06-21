<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\QueryHandlers;

use Model\Delegate\ReadModel\Queries\CheckVoteCountQuery;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Repositories\IVoteRepository;

final class CheckVoteCountQueryHandler
{
    private IDelegateRepository $delegateRepository;
    private IVoteRepository $voteRepository;

    public function __construct(IDelegateRepository $delegateRepository, IVoteRepository $voteRepository)
    {
        $this->delegateRepository = $delegateRepository;
        $this->voteRepository     = $voteRepository;
    }

    public function __invoke(CheckVoteCountQuery $_) : bool
    {
        return $this->delegateRepository->getVotedCount() === $this->voteRepository->getAllVotesCount();
    }
}
