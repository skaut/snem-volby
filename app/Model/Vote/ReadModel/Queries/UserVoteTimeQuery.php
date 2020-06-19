<?php

declare(strict_types=1);

namespace Model\Vote\ReadModel\Queries;

use Model\Vote\ReadModel\QueryHandlers\UserVoteTimeQueryHandler;

/**
 * @see UserVoteTimeQueryHandler
 */
class UserVoteTimeQuery
{
    /** @var int */
    private $personId;

    public function __construct(int $personId)
    {
        $this->personId = $personId;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }
}
