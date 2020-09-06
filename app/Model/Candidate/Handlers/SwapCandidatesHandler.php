<?php

declare(strict_types=1);

namespace Model\Candidate\Handlers;

use Model\Candidate\Commands\SwapCandidates;
use Model\Candidate\Repositories\ICandidateRepository;

final class SwapCandidatesHandler
{
    private ICandidateRepository $candidateRepository;

    public function __construct(ICandidateRepository $candidateRepository) {
        $this->candidateRepository = $candidateRepository;
    }

    public function __invoke(SwapCandidates $command) : void
    {
        $this->candidateRepository->swapCandidates($command->getCandidateUpId(), $command->getCandidateDownId());
    }
}
