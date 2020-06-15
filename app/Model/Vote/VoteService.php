<?php

declare(strict_types=1);

namespace Model\Vote;

use DateTimeImmutable;

class VoteService
{
    private const VOTING_BEGIN_AT = '2020-06-25 09:00:00';
    private const VOTING_END_AT   = '2020-06-29 23:59:00';

    public function __construct()
    {
    }

    public function getVoteBegin() : DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', self::VOTING_BEGIN_AT);
    }

    public function getVoteEnd() : DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', self::VOTING_END_AT);
    }
}
