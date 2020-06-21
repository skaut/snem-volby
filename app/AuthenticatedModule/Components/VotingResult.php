<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use eGen\MessageBus\Bus\QueryBus;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\ReadModel\Queries\DelegatesCountQuery;
use Model\Vote\ReadModel\Queries\VotingResultQuery;

class VotingResult extends BaseControl
{
    /** @var QueryBus */
    private $queryBus;

    public function __construct(
        QueryBus $queryBus
    ) {
        $this->queryBus = $queryBus;
    }

    public function render() : void
    {
        $this->template->setParameters([
            'votingTime'    => $this->queryBus->handle(new VotingTimeQuery()),
            'votingResult' => $this->queryBus->handle(new VotingResultQuery()),
            'delegatesCount'  => $this->queryBus->handle(new DelegatesCountQuery()),
        ]);

        $this->template->render(__DIR__ . '/templates/VotingResult.latte');
    }
}
