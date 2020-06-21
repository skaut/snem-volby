<?php

declare(strict_types=1);

namespace Model\Config\Handlers;

use DateTimeImmutable;
use Model\Config\Commands\PublishVoting;
use Model\Config\Item;
use Model\Config\Repositories\IConfigRepository;

final class PublishVotingHandler
{
    private IConfigRepository $configRepository;

    public function __construct(IConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function __invoke(PublishVoting $command) : void
    {
        $now = new DateTimeImmutable();
        $this->configRepository->setDateTimeValue(Item::VOTING_PUBLISH(), $now);
    }
}
