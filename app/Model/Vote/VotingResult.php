<?php

declare(strict_types=1);

namespace Model\Vote;

use function round;

class VotingResult
{
    private int $yesCount;
    private int $noCount;
    private int $abstainCount;

    private Choice $result;

    public function __construct(int $yesCount, int $noCount, int $abstainCount)
    {
        $this->yesCount     = $yesCount;
        $this->noCount      = $noCount;
        $this->abstainCount = $abstainCount;
    }

    public function getYesCount() : int
    {
        return $this->yesCount;
    }

    public function getYesPercent() : float
    {
        return round($this->getYesCount() / ($this->getTotalVotingCount() / 100), 2);
    }

    public function getNoCount() : int
    {
        return $this->noCount;
    }

    public function getNoPercent() : float
    {
        return round($this->getNoCount() / ($this->getTotalVotingCount() / 100), 2);
    }

    public function getAbstainCount() : int
    {
        return $this->abstainCount;
    }

    public function getTotalVotingCount() : int
    {
        return $this->yesCount + $this->noCount;
    }

    public function getTotalCount() : int
    {
        return $this->getTotalVotingCount() + $this->abstainCount;
    }

    public function getMinVotes() : int
    {
        return (int) round($this->getTotalVotingCount() * (3/5));
    }

    public function getResult() : Choice
    {
        if ($this->getYesCount() >= $this->getMinVotes()) {
            return Choice::YES();
        }

        return Choice::NO();
    }
}
