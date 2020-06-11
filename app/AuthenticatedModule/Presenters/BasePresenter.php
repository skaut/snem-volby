<?php

declare(strict_types=1);

namespace App\AuthenticatedModule;

use Model\Common\UnitId;
use Model\Skautis\SkautisMaintenanceChecker;
use Model\User\ReadModel\Queries\UserUnitIdQuery;
use stdClass;

abstract class BasePresenter extends \App\BasePresenter
{
    protected ?string $backlink = null;
    protected bool $isCurrentUserDelegate;

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

        $this->isCurrentUserDelegate = $this->userService->isDelegate($this->userService->getUserDetail()->ID_Person);
        $this->template->setParameters([
            'isCurrentUserDelegate'=>$this->isCurrentUserDelegate,
        ]);
        if ($this->isCurrentUserDelegate) {
            $this->flashMessage('Přihlášený uživatel je řádným delegátem sněmu.', 'success');
        } else {
            $this->flashMessage('html: Přihlášený uživatel <b>není evidován</b> jako řádný delegát sněmu.', 'danger');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function flashMessage($message, $type = 'info') : stdClass
    {
        $this->redrawControl('flash');

        return parent::flashMessage($message, $type);
    }

    public function getCurrentUnitId() : UnitId
    {
        return $this->queryBus->handle(new UserUnitIdQuery());
    }

    public function renderAccessDenied() : void
    {
        $this->template->setFile(__DIR__ . '/../templates/accessDenied.latte');
    }
}
