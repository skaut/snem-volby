<?php

declare(strict_types=1);

namespace Model\Objection\Handlers;

use Model\Objection\Commands\SaveObjection;
use Model\Objection\Repositories\IObjectionRepository;
use Model\UserService;

final class SaveObjectionHandler
{
    private IObjectionRepository $objectionRepository;

    private UserService $userService;

    public function __construct(
        IObjectionRepository $objectionRepository,
        UserService $userService
    ) {
        $this->objectionRepository = $objectionRepository;
        $this->userService         = $userService;
    }

    public function __invoke(SaveObjection $command) : void
    {
        $this->objectionRepository->saveObjection($command->getObjection());
    }
}
