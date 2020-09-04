<?php

declare(strict_types=1);

namespace Model\Delegate\Handlers;

use eGen\MessageBus\Bus\QueryBus;
use Model\Delegate\Commands\SaveDelegates;
use Model\Delegate\ReadModel\Queries\DelegatesCountQuery;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Delegate\State;
use Model\DTO\Delegate\SkautisDelegate;
use Skautis\Skautis;

final class SaveDelegatesHandler
{
    private IDelegateRepository $delegateRepository;
    private Skautis $skautis;
    private QueryBus $queryBus;
    private int $congressEventId;

    public function __construct(
        int $congressEventId,
        IDelegateRepository $delegateRepository,
        Skautis $skautis,
        QueryBus $queryBus
    ) {
        $this->delegateRepository = $delegateRepository;
        $this->skautis            = $skautis;
        $this->queryBus           = $queryBus;
        $this->congressEventId    = $congressEventId;
    }

    public function __invoke(SaveDelegates $command) : void
    {
        if ($this->queryBus->handle(new DelegatesCountQuery()) !== 0) {
            return;
        }
        $skautisDelegates = $this->skautis->event->DelegateAll(['ID_EventCongress' => $this->congressEventId, 'ID_DelegateState' => State::VALID]);

        $res = [];
        foreach ($skautisDelegates as $d) {
            $res[$d->ID] = new SkautisDelegate(
                $d->ID_Person,
                $d->Person,
                $d->DelegateType,
                $d->RegistrationNumber ?? null,
                $d->Unit ?? null
            );
        }
        $this->delegateRepository->saveDelegates($res);
    }
}
