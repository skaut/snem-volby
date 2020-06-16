<?php

declare(strict_types=1);

namespace Model\Vote;

use DateTimeImmutable;

class VotingTime
{
    private DateTimeImmutable $begin;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $begin, DateTimeImmutable $end)
    {
        $this->begin = $begin;
        $this->end   = $end;
    }

    public static function fromFormat(string $format, string $begin, string $end) : self
    {
        $begin = DateTimeImmutable::createFromFormat($format, $begin);
        $end   = DateTimeImmutable::createFromFormat($format, $end);

        return new self($begin, $end);
    }

    public function getBegin() : DateTimeImmutable
    {
        return $this->begin;
    }

    public function getEnd() : DateTimeImmutable
    {
        return $this->end;
    }
}
