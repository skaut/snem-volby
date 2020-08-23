<?php

declare(strict_types=1);

namespace Model\Candidate\ReadModel\QueryHandlers;

use Model\Candidate\Candidate;
use Model\Candidate\ReadModel\Queries\CandidatesListByFunctionQuery;
use Model\Candidate\Repositories\ICandidateRepository;

final class CandidatesListByFunctionQueryHandler
{
    private ICandidateRepository $candidateRepository;

    public function __construct(ICandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    /** @return array<int, array<int, Candidate>> */
    public function __invoke(CandidatesListByFunctionQuery $_x) : array
    {
        return $this->candidateRepository->getCandidatesByFunction();
    }
}
