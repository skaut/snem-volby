<?php

declare(strict_types=1);

namespace App\AccountancyModule\Components;

use App\AuthenticatedModule\Components\BaseControl;
use App\Forms\BaseForm;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use InvalidArgumentException;
use Model\Commands\Vote\SaveVote;
use Model\Vote;

final class VoteForm extends BaseControl
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
        $this->template->setFile(__DIR__ . '/templates/VoteForm.latte');
        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $form->addSubmit(Vote::YES, 'PRO návrh');
        $form->addSubmit(Vote::NO, 'PROTI návrhu');
        $form->addSubmit(Vote::ABSTAIN, 'Zdržuji se');

        $form->onSuccess[] = function (BaseForm $form) : void {
            $vote = null;
            if ($form[Vote::YES]->isSubmittedBy()) {
                $vote = Vote::YES();
            } elseif ($form[Vote::NO]->isSubmittedBy()) {
                $vote = Vote::NO();
            } elseif ($form[Vote::ABSTAIN]->isSubmittedBy()) {
                $vote = Vote::ABSTAIN();
            } else {
                throw new InvalidArgumentException('Neplatná možnost hlasování!');
            }
            $this->commandBus->handle(new SaveVote($vote));
            $this->redrawControl();
        };

        return $form;
    }
}
