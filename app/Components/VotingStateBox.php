<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use DateTimeImmutable;
use eGen\MessageBus\Bus\QueryBus;
use Model\Vote\ReadModel\Queries\VotingTimeQuery;
use Model\Vote\VotingTime;
use function assert;

final class VotingStateBox extends BaseControl
{
    /** @var QueryBus */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/VotingStateBox.latte');

        $votingTime = $this->queryBus->handle(new VotingTimeQuery());
        assert($votingTime instanceof VotingTime);

        $this->template->setParameters([
            'now' => new DateTimeImmutable(),
            'beginAt' => $votingTime->getBegin(),
            'endAt' => $votingTime->getEnd(),
        ]);

        $this->template->render();
    }
}
