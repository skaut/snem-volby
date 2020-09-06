<?php

declare(strict_types=1);

namespace Model\Candidate\Repositories;

use Model\Candidate\Candidate;
use Model\Candidate\CandidateFunction;
use Model\DTO\Candidate\SkautisCandidate;

interface ICandidateRepository
{
    /**
     * @param SkautisCandidate[] $candidates
     */
    public function saveCandidates(array $candidates) : void;

    public function getCount() : int;

    /** @return  CandidateFunction[] */
    public function getAllFunctions() : array;

    /** @return array<int, array<int, Candidate>> */
    public function getCandidatesByFunction() : array;

    /**
     * @return string[][]
     */
    public function getFunctionCandidatesCounts() : array;

    public function swapCandidates(int $candidateUpId, int $candidateDownId) : void;
}
