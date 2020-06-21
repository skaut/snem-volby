<?php

declare(strict_types=1);

namespace Model\Config\Handlers;

use Model\Config\Commands\SaveVotingTime;
use Model\Config\Item;
use Model\Config\Repositories\IConfigRepository;

final class SaveVotingTimeHandler
{
    private IConfigRepository $configRepository;

    public function __construct(IConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function __invoke(SaveVotingTime $command) : void
    {
        $this->configRepository->setDateTimeValue(Item::VOTING_BEGIN(), $command->getVotingTime()->getBegin());
        $this->configRepository->setDateTimeValue(Item::VOTING_END(), $command->getVotingTime()->getEnd());
    }
}
