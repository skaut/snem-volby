<?php

declare(strict_types=1);

namespace Model\Delegate\ReadModel\Queries;

use Model\Delegate\ReadModel\QueryHandlers\DelegateVoteTimeQueryHandler;

/**
 * @see DelegateVoteTimeQueryHandler
 */
class DelegateVoteTimeQuery
{
    private int $personId;

    public function __construct(int $personId)
    {
        $this->personId = $personId;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }
}
