<?php

declare(strict_types=1);

namespace Model\Vote\Handlers;

use Model\UserService;
use Model\Vote\Commands\SaveVotes;
use Model\Vote\Repositories\IVoteRepository;

final class SaveVotesHandler
{
    private IVoteRepository $voteRepository;

    private UserService $userService;

    public function __construct(
        IVoteRepository $voteRepository,
        UserService $userService
    ) {
        $this->voteRepository = $voteRepository;
        $this->userService    = $userService;
    }

    public function __invoke(SaveVotes $command) : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;
        $this->voteRepository->saveUserVotes($personId, $command->getVotes());
    }
}
