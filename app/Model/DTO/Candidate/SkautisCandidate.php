<?php

declare(strict_types=1);

namespace Model\DTO\Candidate;

use InvalidArgumentException;
use function in_array;
use function sprintf;

class SkautisCandidate
{
    public const SEX_MALE   = 'male';
    public const SEX_FEMALE = 'female';

    private int $id;
    private int $personId;
    private string $sex;
    private string $name;
    private int $functionId;
    private ?int $runningMateId;

    public function __construct(int $id, int $personId, string $sex, string $name, int $functionId, ?int $runningMateId)
    {
        if (! in_array($sex, [self::SEX_MALE, self::SEX_FEMALE])) {
            throw new InvalidArgumentException(sprintf('Sex (%s) of candidate (id=%d) is neither male nor female!', $sex, $id));
        }
        $this->id            = $id;
        $this->personId      = $personId;
        $this->sex           = $sex;
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

    public function getSex() : string
    {
        return $this->sex;
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
