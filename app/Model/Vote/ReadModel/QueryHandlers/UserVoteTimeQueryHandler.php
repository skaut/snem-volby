<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\QueryHandlers;

use DateTimeImmutable;
use Model\Infrastructure\Repositories\VoteRepository;
use Model\Vote\ReadModel\Queries\UserVoteTimeQuery;

final class UserVoteTimeQueryHandler
{
    /** @var VoteRepository */
    private $voteRepository;

    public function __construct(VoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function __invoke(UserVoteTimeQuery $query) : ?DateTimeImmutable
    {
        $userVote = $this->voteRepository->getUserVote($query->getPersonId());

        return $userVote !== null ? $userVote->getCreatedAt() : null;
    }
}
