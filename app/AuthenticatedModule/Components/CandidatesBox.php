<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use eGen\MessageBus\Bus\QueryBus;
use Model\Candidate\ReadModel\Queries\CandidatesListByFunctionQuery;

final class CandidatesBox extends BaseControl
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/CandidatesBox.latte');

        $this->template->setParameters([
            'candidatesList' => $this->queryBus->handle(new CandidatesListByFunctionQuery()),
        ]);

        $this->template->render();
    }
}
