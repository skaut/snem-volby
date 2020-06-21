<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use Model\Vote\ReadModel\Queries\VotingResultQuery;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\VotingResult;

class VotingResultQueryHandler
{
    private IVoteRepository $voteRepository;

    public function __construct(IVoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function __invoke(VotingResultQuery $query) : VotingResult
    {
        return new VotingResult(
            $this->voteRepository->getYesVoteCount(),
            $this->voteRepository->getNoVoteCount(),
            $this->voteRepository->getAbstainVoteCount()
        );
    }
}
