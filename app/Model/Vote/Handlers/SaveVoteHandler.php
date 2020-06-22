<?php

declare(strict_types=1);

namespace Model\Vote\Handlers;

use Model\UserService;
use Model\Vote\Commands\SaveVote;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;

final class SaveVoteHandler
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

    public function __invoke(SaveVote $command) : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;
        $this->voteRepository->saveUserVote($personId, new Vote($command->getChoice()));
    }
}
