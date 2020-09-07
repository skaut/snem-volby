<?php

declare(strict_types=1);

namespace Model\Objection;

use DateInterval;
use DateTimeImmutable;

class ObjectionsTime
{
    private ?DateTimeImmutable $begin;
    private int $DAYS = 3;

    public function __construct(?DateTimeImmutable $begin)
    {
        $this->begin = $begin;
    }

    public static function fromFormat(string $format, string $begin) : self
    {
        $begin = DateTimeImmutable::createFromFormat($format, $begin);

        return new self($begin);
    }

    public function getBegin() : ?DateTimeImmutable
    {
        return $this->begin;
    }

    public function getEnd() : ?DateTimeImmutable
    {
        return $this->begin !== null ? $this->begin->add(new DateInterval('P' . $this->DAYS . 'D')) : null;
    }

    public function areObjectionsInProgress() : bool
    {
        $now = new DateTimeImmutable();

        return $this->getBegin() <= $now && $now < $this->getEnd();
    }
}
