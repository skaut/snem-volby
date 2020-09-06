<?php

declare(strict_types=1);

namespace Model\Candidate\Commands;

use Model\Candidate\Handlers\SwapCandidatesHandler;

/**
 * @see SwapCandidatesHandler
 */
final class SwapCandidates
{
    private int $candidateUpId;

    private int $candidateDownId;

    public function __construct(int $candidateUpId, int $candidateDownId)
    {
        $this->candidateUpId   = $candidateUpId;
        $this->candidateDownId = $candidateDownId;
    }

    public function getCandidateUpId() : int
    {
        return $this->candidateUpId;
    }

    public function getCandidateDownId() : int
    {
        return $this->candidateDownId;
    }
}
