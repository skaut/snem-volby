<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Forms\BaseForm;
use DateTimeImmutable;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Config\Commands\SaveVotingTime;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Vote\VotingTime;

final class VotingTimeForm extends BaseControl
{
    /** @var CommandBus */
    private $commandBus;

    /** @var QueryBus */
    private $queryBus;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus   = $queryBus;
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/VotingTimeForm.latte');
        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $votingTime = $this->queryBus->handle(new VotingTimeQuery());

        $form->addDateTime('votingBegin', 'Hlasování od')
            ->setDefaultValue($votingTime->getBegin() ?? new DateTimeImmutable('8:00:00'));
        $form->addDateTime('votingEnd', 'Hlasování do')
            ->setDefaultValue($votingTime->getEnd() ?? new DateTimeImmutable('+ 4 days 20:00:00'));
        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = function (BaseForm $form, $values) : void {
            $this->commandBus->handle(new SaveVotingTime(new VotingTime($values->votingBegin, $values->votingEnd)));
            $this->flashMessage('Nastavení času hlasování bylo úspěšně uloženo.', 'success');

            $this->redirect('this');
        };

        return $form;
    }
}
