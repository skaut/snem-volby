<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use DateTimeImmutable;
use eGen\MessageBus\Bus\QueryBus;
use Model\Vote;

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

        $this->template->setParameters([
            'now' => new DateTimeImmutable(),
            'beginAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Vote::VOTING_BEGIN_AT),
            'endAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Vote::VOTING_END_AT),
        ]);

        $this->template->render();
    }
}
