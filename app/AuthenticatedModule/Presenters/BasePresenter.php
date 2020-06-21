<?php

declare(strict_types=1);

// phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration

namespace App\AuthenticatedModule;

use Model\Skautis\SkautisMaintenanceChecker;
use stdClass;

abstract class BasePresenter extends \App\BasePresenter
{
    protected ?string $backlink = null;

    private SkautisMaintenanceChecker $skautisMaintenanceChecker;

    public function injectSkautisMaintenanceChecker(SkautisMaintenanceChecker $checker) : void
    {
        $this->skautisMaintenanceChecker = $checker;
    }

    protected function startup() : void
    {
        parent::startup();

        if ($this->skautisMaintenanceChecker->isMaintenance()) {
            throw new SkautisMaintenance();
        }

        if (! $this->getUser()->isLoggedIn()) {
            $this->backlink = $this->storeRequest('+ 3 days');
            if ($this->isAjax()) {
                $this->forward(':Auth:ajax', ['backlink' => $this->backlink]);
            } else {
                $this->redirect(':Default:', ['backlink' => $this->backlink]);
            }
        }

        $this->userService->updateLogoutTime();

        $this->template->setParameters([
            'isDelegate'=>$this->userService->isDelegate(),
            'isSuperuser'=>$this->userService->isSuperUser(),
        ]);
    }

    /**
     * @param string $message
     */
    public function flashMessage($message, string $type = 'info') : stdClass
    {
        $this->redrawControl('flash');

        return parent::flashMessage($message, $type);
    }

    public function renderAccessDenied() : void
    {
        $this->template->setFile(__DIR__ . '/../templates/accessDenied.latte');
    }
}
