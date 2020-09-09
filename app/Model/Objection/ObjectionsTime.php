<?php

declare(strict_types=1);

namespace Model\Objection;

use DateInterval;
use DateTimeImmutable;

class ObjectionsTime
{
    private ?DateTimeImmutable $begin;
    private ?DateTimeImmutable $publish;
    private int $DAYS = 3;

    public function __construct(?DateTimeImmutable $begin, ?DateTimeImmutable $publish)
    {
        $this->begin   = $begin;
        $this->publish = $publish;
    }

    public function getBegin() : ?DateTimeImmutable
    {
        return $this->begin;
    }

    public function getEnd() : ?DateTimeImmutable
    {
        return $this->publish !== null ? $this->publish->add(new DateInterval('P' . $this->DAYS . 'D')) : null;
    }

    public function areObjectionsInProgress() : bool
    {
        $now = new DateTimeImmutable();

        return $this->getBegin() <= $now && ($this->getEnd() === null || $now < $this->getEnd());
    }
}
