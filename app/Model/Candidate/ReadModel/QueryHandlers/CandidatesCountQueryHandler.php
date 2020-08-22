<?php

declare(strict_types=1);

namespace Model\Candidate\ReadModel\QueryHandlers;

use Model\Candidate\ReadModel\Queries\CandidatesCountQuery;
use Model\Candidate\Repositories\ICandidateRepository;

final class CandidatesCountQueryHandler
{
    private ICandidateRepository $candidateRepository;

    public function __construct(ICandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(CandidatesCountQuery $_x) : int
    {
        return $this->candidateRepository->getCount();
    }
}
