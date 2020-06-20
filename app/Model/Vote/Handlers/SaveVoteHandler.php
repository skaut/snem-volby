<?php

declare(strict_types=1);

namespace Model\Vote\Handlers;

use DateTimeImmutable;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\UserService;
use Model\Vote\Commands\SaveVote;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;

final class SaveVoteHandler
{
    private IVoteRepository $voteRepository;

    private IDelegateRepository $delegateRepository;

    private UserService $userService;

    public function __construct(
        IVoteRepository $voteRepository,
        IDelegateRepository $delegateRepository,
        UserService $userService
    ) {
        $this->voteRepository     = $voteRepository;
        $this->delegateRepository = $delegateRepository;
        $this->userService        = $userService;
    }

    public function __invoke(SaveVote $command) : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;
        $delegate = $this->delegateRepository->getDelegate($personId);
        $delegate->setVotedAt(new DateTimeImmutable());
        $this->voteRepository->saveUserVote(new Vote($command->getChoice()), $delegate);
    }
}
