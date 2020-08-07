<?php

declare(strict_types=1);

namespace Model\DTO\Candidate;

class SkautisCandidate
{
    private int $id;
    private int $personId;
    private string $name;
    private int $functionId;
    private ?int $runningMateId;

    public function __construct(int $id, int $personId, string $name, int $functionId, ?int $runningMateId)
    {
        $this->id            = $id;
        $this->personId      = $personId;
        $this->name          = $name;
        $this->functionId    = $functionId;
        $this->runningMateId = $runningMateId;
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

    public function getRunningMateId() : ?int
    {
        return $this->runningMateId;
    }

    public function hasRunningMate() : bool
    {
        return $this->runningMateId !== null;
    }
}
