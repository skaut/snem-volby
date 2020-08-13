<?php

declare(strict_types=1);

namespace Model\Candidate\Repositories;

use Model\DTO\Candidate\SkautisCandidate;

interface ICandidateRepository
{
    /**
     * @param SkautisCandidate[] $candidates
     */
    public function saveCandidates(array $candidates) : void;

    public function getCount() : int;

    /**
     * @return string[][]
     */
    public function getFunctionCandidatesCounts() : array;
}
