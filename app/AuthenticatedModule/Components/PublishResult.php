<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Factories\IVotingResultFactory;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Config\Commands\PublishVoting;
use Model\Config\ReadModel\Queries\VotingPublishedQuery;
use Model\Config\ReadModel\Queries\VotingTimeQuery;

class PublishResult extends BaseControl
{
    private CommandBus              $commandBus;
    private QueryBus                $queryBus;
    private IVotingResultFactory    $votingResultFactory;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        IVotingResultFactory $votingResultFactory
    ) {
        $this->commandBus          = $commandBus;
        $this->queryBus            = $queryBus;
        $this->votingResultFactory = $votingResultFactory;
    }

    public function render() : void
    {
        $this->template->setParameters([
            'votingTime' => $this->queryBus->handle(new VotingTimeQuery()),
            'published' => $this->queryBus->handle(new VotingPublishedQuery()),
        ]);
        $this->template->render(__DIR__ . '/templates/PublishResult.latte');
    }

    public function handlePublishResult() : void
    {
        $this->commandBus->handle(new PublishVoting());

        $this->reload();
    }

    protected function createComponentVotingResult() : VotingResult
    {
        return $this->votingResultFactory->create();
    }
}
