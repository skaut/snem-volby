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
        $yesCount     = $this->voteRepository->getYesVoteCount();
        $noCount      = $this->voteRepository->getNoVoteCount();
        $abstainCount = $this->voteRepository->getAbstainVoteCount();

        return new VotingResult($yesCount, $noCount, $abstainCount);
    }
}
