<?php

declare(strict_types=1);

namespace Model\Config\ReadModel\QueryHandlers;

use DateTimeImmutable;
use Model\Config\Item;
use Model\Config\ReadModel\Queries\VotingPublishedQuery;
use Model\Config\Repositories\IConfigRepository;

final class VotingPublishedQueryHandler
{
    private IConfigRepository $configRepository;

    public function __construct(IConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function __invoke(VotingPublishedQuery $query) : ?DateTimeImmutable
    {
        return $this->configRepository->getDateTimeValue(Item::VOTING_PUBLISH());
    }
}
