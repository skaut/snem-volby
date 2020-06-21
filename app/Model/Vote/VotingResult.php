<?php

declare(strict_types=1);

namespace Model\Vote;

use function round;

class VotingResult
{
    private int $yesCount;
    private int $noCount;
    private int $abstainCount;

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
        return $this->yesCount === 0 ? 0 : $this->formatPercent($this->yesCount / $this->getTotalVotingCount());
    }

    public function getNoPercent() : float
    {
        return $this->noCount === 0 ? 0 : $this->formatPercent($this->noCount / $this->getTotalVotingCount());
    }

    public function getAbstainPercent() : float
    {
        return $this->abstainCount === 0 ? 0 : $this->formatPercent($this->abstainCount / $this->getTotalVotingCount());
    }

    public function getNoCount() : int
    {
        return $this->noCount;
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
        return (int) max(1, ceil($this->getTotalVotingCount() * (3/5)));
    }

    public function getResult() : Choice
    {
        if ($this->getYesCount() >= $this->getMinVotes()) {
            return Choice::YES();
        }

        return Choice::NO();
    }

    private function formatPercent(float $number) : float
    {
        return round($number * 100, 2);
    }
}
