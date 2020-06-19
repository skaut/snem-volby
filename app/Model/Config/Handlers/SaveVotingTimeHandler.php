<?php

declare(strict_types=1);

namespace Model\Config\Handlers;

use DateTimeImmutable;
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
        $this->configRepository->setValue(Item::VOTING_BEGIN(), $command->getVotingTime()->getBegin()->format(DateTimeImmutable::ISO8601));
        $this->configRepository->setValue(Item::VOTING_END(), $command->getVotingTime()->getEnd()->format(DateTimeImmutable::ISO8601));
    }
}
