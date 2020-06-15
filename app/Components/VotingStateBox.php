<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use DateTimeImmutable;
use eGen\MessageBus\Bus\QueryBus;
use Model\Vote\VoteService;

final class VotingStateBox extends BaseControl
{
    /** @var QueryBus */
    private $queryBus;

    private VoteService $voteService;

    public function __construct(QueryBus $queryBus, VoteService $voteService)
    {
        $this->queryBus    = $queryBus;
        $this->voteService = $voteService;
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/VotingStateBox.latte');

        $this->template->setParameters([
            'now' => new DateTimeImmutable(),
            'beginAt' => $this->voteService->getVoteBegin(),
            'endAt' => $this->voteService->getVoteEnd(),
        ]);

        $this->template->render();
    }
}
