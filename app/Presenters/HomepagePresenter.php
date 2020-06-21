<?php

declare(strict_types=1);

namespace App;

use Model\Config\ReadModel\Queries\VotingPublishedQuery;

final class HomepagePresenter extends BasePresenter
{
    public function renderDefault() : void
    {
        $this->template->setParameters([
            'hideNavBar' => true,
            'isResultPublished' => $this->queryBus->handle(new VotingPublishedQuery()) !== null,
        ]);
    }
}
