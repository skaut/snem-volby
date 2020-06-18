<?php

declare(strict_types=1);

namespace Model\Vote\Handlers;

use Model\Infrastructure\Repositories\VoteRepository;
use Model\UserService;
use Model\Vote\Commands\SaveVote;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\UsersVote;
use Model\Vote\Vote;

final class SaveVoteHandler
{
    /** @var IVoteRepository */
    private $voteRepository;

    private UserService $userService;

    public function __construct(VoteRepository $voteRepository, UserService $userService)
    {
        $this->voteRepository = $voteRepository;
        $this->userService    = $userService;
    }

    public function __invoke(SaveVote $command) : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;
        $this->voteRepository->saveUserVote(new Vote($command->getChoice()), new UsersVote($personId));
    }
}
