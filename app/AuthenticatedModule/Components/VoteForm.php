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
use Model\Commands\Vote\SaveVote;
use Model\Infrastructure\Repositories\VoteRepository;
use Model\UserService;
use Model\Vote\Option;

final class VoteForm extends BaseControl
{
    /** @var CommandBus */
    private $commandBus;

    /** @var QueryBus */
    private $queryBus;

    /** @var VoteRepository */
    private $voteRepository;

    /** @var UserService */
    private $userService;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        VoteRepository $voteRepository,
        UserService $userService
    ) {
        $this->commandBus     = $commandBus;
        $this->queryBus       = $queryBus;
        $this->voteRepository = $voteRepository;
        $this->userService    = $userService;
    }

    public function render() : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;

        $this->template->setFile(__DIR__ . '/templates/VoteForm.latte');
        $this->template->userVote = $this->voteRepository->getUserVote($personId);
        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $form->addSubmit(Option::YES);
        $form->addSubmit(Option::NO);
        $form->addSubmit(Option::ABSTAIN);

        $form->onSuccess[] = function (BaseForm $form) : void {
            $vote     = null;
            $voteName = null;
            if ($form[Option::YES]->isSubmittedBy()) {
                $vote     = Option::YES();
                $voteName = "PRO";
            } elseif ($form[Option::NO]->isSubmittedBy()) {
                $vote     = Option::NO();
                $voteName = "PROTI";
            } elseif ($form[Option::ABSTAIN]->isSubmittedBy()) {
                $vote     = Option::ABSTAIN();
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
