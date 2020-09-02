<?php

declare(strict_types=1);

namespace Model\User\Handlers;

use eGen\MessageBus\QueryBus\IQueryBus;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\User\Commands\SaveFirstLogin;
use Model\Vote\VotingTime;
use function assert;

final class SaveFirstLoginHandler
{
    private IDelegateRepository $delegateRepository;

    private IQueryBus $queryBus;

    public function __construct(IDelegateRepository $delegateRepository, IQueryBus $queryBus)
    {
        $this->delegateRepository = $delegateRepository;
        $this->queryBus           = $queryBus;
    }

    public function __invoke(SaveFirstLogin $query) : void
    {
        $votingTime = $this->queryBus->handle(new VotingTimeQuery());
        assert($votingTime instanceof VotingTime);
        if (! $votingTime->isVotingInProgress()) {
            return;
        }

        $this->delegateRepository->setDelegateFirstLogin($query->getPersonId());
    }
}
