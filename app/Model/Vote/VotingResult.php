<?php

declare(strict_types=1);

namespace Model\Vote;

use function ceil;
use function round;

class VotingResult
{
    private int $yesCount;
    private int $noCount;
    private int $abstainCount;
    private int $totalCountOfDelegates;

    public function __construct(int $yesCount, int $noCount, int $abstainCount, int $totalCountOfDelegates)
    {
        $this->yesCount              = $yesCount;
        $this->noCount               = $noCount;
        $this->abstainCount          = $abstainCount;
        $this->totalCountOfDelegates = $totalCountOfDelegates;
    }

    public function getYesCount() : int
    {
        return $this->yesCount;
    }

    public function getYesPercent() : float
    {
        return $this->yesCount === 0 ? 0 : round(($this->yesCount / $this->totalCountOfDelegates)*100, 2);
    }

    public function getNoCount() : int
    {
        return $this->noCount;
    }

    public function getAbstainCount() : int
    {
        return $this->abstainCount + $this->getNotVotedCount();
    }

    public function getNotVotedCount() : int
    {
        return $this->totalCountOfDelegates - ($this->yesCount + $this->noCount + $this->abstainCount);
    }

    public function getTotalVotingCount() : int
    {
        return $this->yesCount + $this->noCount;
    }

    public function getCountOfVotes() : int
    {
        return $this->yesCount + $this->noCount + $this->abstainCount;
    }

    public function getMinVotes() : int
    {
        return (int) ceil($this->totalCountOfDelegates * (3/5));
    }

    public function getResult() : Choice
    {
        if ($this->getYesCount() >= $this->getMinVotes()) {
            return Choice::YES();
        }

        return Choice::NO();
    }
}
