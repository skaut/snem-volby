<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\BasePresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidStateException;
use stdClass;

/**
 * @property-read Template $template
 * @property-read BasePresenter $presenter
 */
abstract class BaseControl extends Control
{
    abstract public function render() : void;

    /**
     * {@inheritDoc}
     */
    protected function createTemplate() : ITemplate
    {
        $template = parent::createTemplate();

        $template->getLatte()->addFilter('formatdate', '\\App\\Utils\\Helpers::formatDate');
        $template->getLatte()->addFilter('formatdatetime', '\\App\\Utils\\Helpers::formatDateTime');

        return $template;
    }

    /**
     * {@inheritDoc}
     */
    public function getPresenter() : ?BasePresenter
    {
        $presenter = parent::getPresenter();

        if (! $presenter instanceof BasePresenter) {
            throw new InvalidStateException(
                'Presenter using BaseControl derived controls must inherit from ' . BasePresenter::class
            );
        }

        return $presenter;
    }

    /**
     * {@inheritDoc}
     */
    public function flashMessage($message, $type = 'info') : stdClass
    {
        return $this->getPresenter()->flashMessage($message, $type);
    }

    protected function reload(?string $message = null, string $type = 'info') : void
    {
        if ($message !== null) {
            $this->flashMessage($message, $type);
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl();
        } else {
            $this->redirect('this');
        }
    }
}
