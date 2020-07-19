<?php

declare(strict_types=1);

namespace Model\Candidate\Repositories;

use stdClass;

interface ICandidateRepository
{
    /**
     * @param stdClass[] $candidates
     */
    public function saveCandidates(array $candidates) : void;

    public function getCount() : int;
}
