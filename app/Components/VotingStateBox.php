<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Candidate\Commands\SwapCandidates;
use Model\Config\ReadModel\Queries\VotingPublishedQuery;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\ReadModel\Queries\DelegatesCountQuery;
use Model\UserService;
use Model\Vote\ReadModel\Queries\VotingResultQuery;
use Nette\Security\User;

final class VotingStateBox extends BaseControl
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private UserService $userService;
    private User $user;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus, UserService $userService, User $user)
    {
        $this->queryBus    = $queryBus;
        $this->commandBus  = $commandBus;
        $this->userService = $userService;
        $this->user        = $user;
    }

    public function render(bool $showUnpublished = false) : void
    {
        $this->template->setFile(__DIR__ . '/templates/VotingStateBox.latte');

        $this->template->setParameters([
            'votingTime'    => $this->queryBus->handle(new VotingTimeQuery()),
            'votingResult' => $this->queryBus->handle(new VotingResultQuery()),
            'delegatesCount' => $this->queryBus->handle(new DelegatesCountQuery()),
            'showResult' => $this->queryBus->handle(new VotingPublishedQuery()) !== null || ($this->user->isLoggedIn() && $this->userService->isAdmin() && $showUnpublished),
            'canEdit' => $this->canEdit() && $showUnpublished
        ]);

        $this->template->render();
    }

    public function handleSwap(?string $candidateUpId, ?string $candidateDownId) : void
    {
        if ($this->canEdit()) {
            $this->commandBus->handle(new SwapCandidates((int)$candidateUpId, (int)$candidateDownId));
        }
        $this->redirect('this');
    }

    private function canEdit() : bool {
        return $this->queryBus->handle(new VotingPublishedQuery()) === null && $this->user->isLoggedIn() && $this->userService->isAdmin();
    }
}
