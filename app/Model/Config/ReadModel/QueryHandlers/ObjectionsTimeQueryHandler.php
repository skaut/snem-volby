<?php

declare(strict_types=1);

namespace Model\Config\ReadModel\QueryHandlers;

use Model\Config\Item;
use Model\Config\ReadModel\Queries\ObjectionsTimeQuery;
use Model\Config\Repositories\IConfigRepository;
use Model\Objection\ObjectionsTime;

final class ObjectionsTimeQueryHandler
{
    private IConfigRepository $configRepository;

    public function __construct(IConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function __invoke(ObjectionsTimeQuery $_) : ObjectionsTime
    {
        return new ObjectionsTime(
            $this->configRepository->getDateTimeValue(Item::VOTING_BEGIN()) ?: null,
            $this->configRepository->getDateTimeValue(Item::VOTING_PUBLISH()) ?: null
        );
    }
}
