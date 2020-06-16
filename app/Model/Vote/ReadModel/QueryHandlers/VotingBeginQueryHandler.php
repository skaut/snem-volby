<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use DateTimeImmutable;
use Model\Vote\ReadModel\Queries\VotingBeginQuery;

final class VotingBeginQueryHandler
{
    private const VOTING_BEGIN_AT = '2020-06-25 09:00:00';

    public function __construct()
    {
    }

    public function __invoke(VotingBeginQuery $_) : DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', self::VOTING_BEGIN_AT);
    }
}
