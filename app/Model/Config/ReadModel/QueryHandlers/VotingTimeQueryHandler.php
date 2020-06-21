<?php

declare(strict_types=1);

namespace Model\Config\ReadModel\QueryHandlers;

use Model\Config\Item;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Config\Repositories\IConfigRepository;
use Model\Vote\VotingTime;

final class VotingTimeQueryHandler
{
    private IConfigRepository $configRepository;

    public function __construct(IConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function __invoke(VotingTimeQuery $_) : VotingTime
    {
        return new VotingTime(
            $this->configRepository->getDateTimeValue(Item::VOTING_BEGIN()) ?: null,
            $this->configRepository->getDateTimeValue(Item::VOTING_END()) ?: null
        );
    }
}
