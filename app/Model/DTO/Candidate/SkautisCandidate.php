<?php

declare(strict_types=1);

namespace Model\DTO\Candidate;

class SkautisCandidate
{
    private int $id;
    private int $personId;
    private string $name;
    private int $functionId;
    private ?int $candidateWith;

    public function __construct(int $id, int $personId, string $name, int $functionId, ?int $candidateWith)
    {
        $this->id            = $id;
        $this->personId      = $personId;
        $this->name          = $name;
        $this->functionId    = $functionId;
        $this->candidateWith = $candidateWith;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFunctionId() : int
    {
        return $this->functionId;
    }

    public function getCandidateWith() : ?int
    {
        return $this->candidateWith;
    }

    public function isCandidateWith() : bool
    {
        return $this->candidateWith !== null;
    }
}
