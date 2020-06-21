<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use eGen\MessageBus\Bus\QueryBus;
use Model\Config\ReadModel\Queries\VotingTimeQuery;

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
            'votingTime' => $this->queryBus->handle(new VotingTimeQuery()),
        ]);

        $this->template->render();
    }
}
