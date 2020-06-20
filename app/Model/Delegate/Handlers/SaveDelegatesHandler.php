<?php

declare(strict_types=1);

namespace Model\Delegate\Handlers;

use Model\Delegate\Commands\SaveDelegates;
use Model\Delegate\Delegate;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\UserService;

final class SaveDelegatesHandler
{
    private IDelegateRepository $delegateRepository;

    private UserService $userService;

    public function __construct(IDelegateRepository $delegateRepository, UserService $userService)
    {
        $this->delegateRepository = $delegateRepository;
        $this->userService        = $userService;
    }

    public function __invoke(SaveDelegates $command) : void
    {
        $delegates = $this->userService->getValidDelegates();
        foreach ($delegates as $delegate) {
            $this->delegateRepository->saveDelegate(new Delegate($delegate->ID_Person));
        }
    }
}
