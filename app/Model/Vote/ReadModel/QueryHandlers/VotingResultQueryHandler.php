<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use Model\Delegate\Repositories\IDelegateRepository;
use Model\Infrastructure\Repositories\DelegateRepository;
use Model\Vote\ReadModel\Queries\VotingResultQuery;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\VotingResult;

class VotingResultQueryHandler
{
    private IVoteRepository $voteRepository;
    private IDelegateRepository $delegateRepository;

    public function __construct(IVoteRepository $voteRepository, DelegateRepository $delegateRepository)
    {
        $this->voteRepository     = $voteRepository;
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(VotingResultQuery $query) : VotingResult
    {
        return new VotingResult(
            $this->voteRepository->getYesVoteCount(),
            $this->voteRepository->getNoVoteCount(),
            $this->voteRepository->getAbstainVoteCount(),
            $this->delegateRepository->getCount()
        );
    }
}
