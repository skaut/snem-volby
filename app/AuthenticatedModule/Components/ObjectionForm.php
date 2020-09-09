<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Forms\BaseForm;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Config\ReadModel\Queries\ObjectionsTimeQuery;
use Model\Infrastructure\Repositories\DelegateRepository;
use Model\Objection\Commands\SaveObjection;
use Model\Objection\Objection;
use Model\UserService;
use Nette\Application\UI\Form;
use Throwable;

final class ObjectionForm extends BaseControl
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    private UserService $userService;

    private DelegateRepository $delegateRepository;

    private bool $isUserDelegate;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UserService $userService,
        DelegateRepository $delegateRepository
    ) {
        $this->commandBus         = $commandBus;
        $this->queryBus           = $queryBus;
        $this->userService        = $userService;
        $this->delegateRepository = $delegateRepository;
        $this->isUserDelegate     = $this->userService->isDelegate();
    }

    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/ObjectionForm.latte');
        $this->template->setParameters([
            'isUserDelegate' => $this->isUserDelegate,
        ]);

        if ($this->isUserDelegate) {
            $this->template->setParameters([
                'objectionsTime' => $this->queryBus->handle(new ObjectionsTimeQuery()),
            ]);
        }

        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form = new BaseForm();

        $form->addTextArea('text', 'Text námitky')
            ->addRule(Form::FILLED, 'Text námitky musí být vyplněn');

        $form->addSubmit('save');

        $form->onSuccess[] = function (Form $form) : void {
            $values = $form->getValues();

            $delegate = $this->delegateRepository->getDelegate($this->userService->getUserDetail()->ID_Person);

            $objection = new Objection($values['text'], $delegate);

            $this->commandBus->handle(new SaveObjection($objection));
            $this->flashMessage('Tvoje námitka byla úspěšně uložena.', 'success');

            $this->redirect('this');
        };

        return $form;
    }
}
