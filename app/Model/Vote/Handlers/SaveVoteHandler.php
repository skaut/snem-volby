<?php

declare(strict_types=1);

namespace Model\Cashbook\Handlers\Cashbook;

use Model\Commands\Vote\SaveVote;
use Model\Infrastructure\Repositories\VoteRepository;
use Model\UserService;
use Model\UsersVote;
use Model\Vote;
use Model\Vote\Repositories\IVoteRepository;

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
        $this->voteRepository->saveUserVote(new Vote($command->getOption()), new UsersVote($personId));
    }
}
