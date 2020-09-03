<?php

declare(strict_types=1);

namespace Model\Vote\Commands;

use Model\Vote\Handlers\SaveVotesHandler;
use Model\Vote\Vote;

/**
 * @see SaveVotesHandler
 */
final class SaveVotes
{
    /** @var Vote[] */
    private array $votes;

    /** @param Vote[] $votes */
    public function __construct(array $votes)
    {
        $this->votes = $votes;
    }

    /** @return Vote[] */
    public function getVotes() : array
    {
        return $this->votes;
    }
}
