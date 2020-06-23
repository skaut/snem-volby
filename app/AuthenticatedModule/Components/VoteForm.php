<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Forms\BaseForm;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use InvalidArgumentException;
use Model\Config\ReadModel\Queries\VotingPublishedQuery;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\ReadModel\Queries\DelegateVoteTimeQuery;
use Model\UserService;
use Model\Vote\Choice;
use Model\Vote\Commands\SaveVote;
use Throwable;

final class VoteForm extends BaseControl
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    private UserService $userService;

    private bool $isUserDelegate;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UserService $userService
    ) {
        $this->commandBus     = $commandBus;
        $this->queryBus       = $queryBus;
        $this->userService    = $userService;
        $this->isUserDelegate = $this->userService->isDelegate();
    }

    public function render() : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;

        $this->template->setFile(__DIR__ . '/templates/VoteForm.latte');
        $this->template->setParameters([
            'isUserDelegate'    => $this->isUserDelegate,
        ]);

        if ($this->isUserDelegate) {
            $this->template->setParameters([
                'userVoteTime' => $this->queryBus->handle(new DelegateVoteTimeQuery($personId)),
                'isResultPublished' => $this->queryBus->handle(new VotingPublishedQuery()) !== null,
                'votingTime'   => $this->queryBus->handle(new VotingTimeQuery()),
            ]);
        }

        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $yesButton     = $form->addSubmit(Choice::YES, 'PRO návrh');
        $noButton      = $form->addSubmit(Choice::NO, 'PROTI návrhu');
        $abstainButton = $form->addSubmit(Choice::ABSTAIN, 'Zdržuji se');

        $form->onSuccess[] = function () use ($yesButton, $noButton, $abstainButton) : void {
            $vote = null;
            if ($yesButton->isSubmittedBy()) {
                $vote     = Choice::YES();
                $voteName = 'PRO';
            } elseif ($noButton->isSubmittedBy()) {
                $vote     = Choice::NO();
                $voteName = 'PROTI';
            } elseif ($abstainButton->isSubmittedBy()) {
                $vote     = Choice::ABSTAIN();
                $voteName = 'ZDRŽUJI SE';
            } else {
                throw new InvalidArgumentException('Neplatná možnost hlasování!');
            }

            try {
                $this->commandBus->handle(new SaveVote($vote));
                $this->flashMessage('Tvůj hlas "' . $voteName . '" byl úspěšně uložen.', 'success');
            } catch (UniqueConstraintViolationException $e) {
                $this->flashMessage('Tvůj hlas byl odeslán již dříve.', 'danger');
            } catch (Throwable $e) {
                $this->flashMessage('Hlasování bylo neúspěšné.', 'danger');
            }

            $this->redrawControl();
        };

        return $form;
    }
}
