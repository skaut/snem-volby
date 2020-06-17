<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use Model\Vote\ReadModel\Queries\VotingTimeQuery;
use Model\Vote\VotingTime;

final class VotingTimeQueryHandler
{
    private const VOTING_BEGIN_AT = '2020-06-25 09:00:00';
    private const VOTING_END_AT   = '2020-06-29 23:59:00';

    public function __invoke(VotingTimeQuery $_) : VotingTime
    {
        return VotingTime::fromFormat('Y-m-d H:i:s', self::VOTING_BEGIN_AT, self::VOTING_END_AT);
    }
}
