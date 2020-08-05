<?php

declare(strict_types=1);

namespace Model\User\Handlers;

use Model\Delegate\Repositories\IDelegateRepository;
use Model\User\Commands\SaveFirstLogin;

final class SaveFirstLoginHandler
{
    private IDelegateRepository $delegateRepository;

    public function __construct(IDelegateRepository $delegateRepository)
    {
        $this->delegateRepository = $delegateRepository;
    }

    public function __invoke(SaveFirstLogin $query) : void
    {
        $this->delegateRepository->setDelegateFirstLogin($query->getPersonId());
    }
}
