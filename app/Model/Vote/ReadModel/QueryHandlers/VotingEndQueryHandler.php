<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use DateTimeImmutable;
use Model\Vote\ReadModel\Queries\VotingEndQuery;

final class VotingEndQueryHandler
{
    private const VOTING_END_AT = '2020-06-29 23:59:00';

    public function __construct()
    {
    }

    public function __invoke(VotingEndQuery $_) : DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', self::VOTING_END_AT);
    }
}
