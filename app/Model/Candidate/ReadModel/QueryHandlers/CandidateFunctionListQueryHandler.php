<?php

declare(strict_types=1);

namespace Model\Candidate\ReadModel\QueryHandlers;

use Model\Candidate\CandidateFunction;
use Model\Candidate\ReadModel\Queries\CandidateFunctionListQuery;
use Model\Candidate\Repositories\ICandidateRepository;

final class CandidateFunctionListQueryHandler
{
    private ICandidateRepository $candidateRepository;

    public function __construct(ICandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    /** @return CandidateFunction[] */
    public function __invoke(CandidateFunctionListQuery $_x) : array
    {
        return $this->candidateRepository->getAllFunctions();
    }
}
