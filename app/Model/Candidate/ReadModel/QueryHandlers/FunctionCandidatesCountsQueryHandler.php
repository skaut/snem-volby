<?php

declare(strict_types=1);

namespace Model\Candidate\ReadModel\QueryHandlers;

use Model\Candidate\ReadModel\Queries\FunctionsCandidatesCountsQuery;
use Model\Candidate\Repositories\ICandidateRepository;

final class FunctionCandidatesCountsQueryHandler
{
    private ICandidateRepository $candidateRepository;

    public function __construct(ICandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(FunctionsCandidatesCountsQuery $_x) : array
    {
        return $this->candidateRepository->getFunctionsCounts();
    }
}
