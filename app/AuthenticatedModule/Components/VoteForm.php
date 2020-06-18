<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use App\Forms\BaseForm;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Exception;
use InvalidArgumentException;
use Model\UserService;
use Model\Vote\Choice;
use Model\Vote\Commands\SaveVote;
use Model\Vote\ReadModel\Queries\UserVoteTimeQuery;
use Model\User\ReadModel\Queries\IsUserDelegateQuery;
use Model\Vote\ReadModel\Queries\VotingTimeQuery;

final class VoteForm extends BaseControl
{
    /** @var CommandBus */
    private $commandBus;

    /** @var QueryBus */
    private $queryBus;

    /** @var UserService */
    private $userService;

    private bool $isUserDelegate;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UserService $userService
    ) {
        $this->commandBus     = $commandBus;
        $this->queryBus       = $queryBus;
        $this->userService    = $userService;
        $this->isUserDelegate = $this->queryBus->handle(new IsUserDelegateQuery());
    }

    public function render() : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;

        $this->template->setFile(__DIR__ . '/templates/VoteForm.latte');
        $this->template->userVoteTime = $this->queryBus->handle(new UserVoteTimeQuery($personId));
        $this->template->votingTime = $this->queryBus->handle(new VotingTimeQuery());
        $this->template->setParameters([
            'isUserDelegate' => $this->isUserDelegate,
        ]);
        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $form->addSubmit(Choice::YES);
        $form->addSubmit(Choice::NO);
        $form->addSubmit(Choice::ABSTAIN);

        $form->onSuccess[] = function (BaseForm $form) : void {
            $vote     = null;
            $voteName = null;
            if ($form[Choice::YES]->isSubmittedBy()) {
                $vote     = Choice::YES();
                $voteName = "PRO";
            } elseif ($form[Choice::NO]->isSubmittedBy()) {
                $vote     = Choice::NO();
                $voteName = "PROTI";
            } elseif ($form[Choice::ABSTAIN]->isSubmittedBy()) {
                $vote     = Choice::ABSTAIN();
                $voteName = "ZDRŽUJI SE";
            } else {
                throw new InvalidArgumentException('Neplatná možnost hlasování!');
            }

            try {
                $this->commandBus->handle(new SaveVote($vote));
                $this->flashMessage('Tvůj hlas "' . $voteName . '" byl úspěšně uložen.', 'success');
            } catch (UniqueConstraintViolationException $e) {
                $this->flashMessage('Tvůj hlas byl odeslán již dříve.', 'danger');
            } catch (Exception $e) {
                $this->flashMessage('Hlasování bylo neúspěšné.', 'danger');
            }

            $this->redrawControl();
        };

        return $form;
    }
}
