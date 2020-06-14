<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;
use App\Forms\BaseForm;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use InvalidArgumentException;
use Model\Commands\Vote\SaveVote;
use Model\Vote\Option;

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

        $form->addSubmit(Option::YES, 'PRO návrh')
            ->setHtmlAttribute('class', 'btn btn-success');
        $form->addSubmit(Option::NO, 'PROTI návrhu')
            ->setHtmlAttribute('class', 'btn btn-danger');
        $form->addSubmit(Option::ABSTAIN, 'Zdržuji se')
            ->setHtmlAttribute('class', 'btn btn-warning');

        $form->onSuccess[] = function (BaseForm $form) : void {
            $vote = null;
            if ($form[Option::YES]->isSubmittedBy()) {
                $vote = Option::YES();
            } elseif ($form[Option::NO]->isSubmittedBy()) {
                $vote = Option::NO();
            } elseif ($form[Option::ABSTAIN]->isSubmittedBy()) {
                $vote = Option::ABSTAIN();
            } else {
                throw new InvalidArgumentException('Neplatná možnost hlasování!');
            }
            $this->commandBus->handle(new SaveVote($vote));
            $this->redrawControl();
        };

        return $form;
    }
}
