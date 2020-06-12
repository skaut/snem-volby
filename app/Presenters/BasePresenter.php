<?php

declare(strict_types=1);

namespace App;

use App\Components\LoginPanel;
use App\Components\TipBox;
use App\Components\VotingStateBox;
use App\Factories\ILoginPanelFactory;
use App\Factories\ITipBoxFactory;
use App\Factories\IVotingStateBoxFactory;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Common\Services\NotificationsCollector;
use Model\UserService;
use Nette;
use Nette\Application\IResponse;
use Psr\Log\LoggerInterface;
use Skautis\Wsdl\AuthenticationException;
use function array_key_last;
use function explode;
use function sprintf;

/**
 * @property-read Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected UserService $userService;
    protected CommandBus $commandBus;
    protected QueryBus $queryBus;

    private ILoginPanelFactory $loginPanelFactory;
    private NotificationsCollector $notificationsCollector;
    protected LoggerInterface $logger;
    private IVotingStateBoxFactory $votingStateBoxFactory;
    private ITipBoxFactory $tipBoxFactory;

    public function injectAll(
        UserService $userService,
        CommandBus $commandBus,
        QueryBus $queryBus,
        ILoginPanelFactory $loginPanelFactory,
        NotificationsCollector $notificationsCollector,
        LoggerInterface $logger,
        IVotingStateBoxFactory $votingStateBoxFactory,
        ITipBoxFactory $tipBoxFactory
    ) : void {
        $this->userService            = $userService;
        $this->commandBus             = $commandBus;
        $this->queryBus               = $queryBus;
        $this->loginPanelFactory      = $loginPanelFactory;
        $this->logger                 = $logger;
        $this->notificationsCollector = $notificationsCollector;
        $this->votingStateBoxFactory  = $votingStateBoxFactory;
        $this->tipBoxFactory          = $tipBoxFactory;
    }

    protected function startup() : void
    {
        parent::startup();

        //adresář s částmi šablon pro použití ve více modulech
        $this->template->setParameters([
            'backlink' => $backlink = $this->getParameter('backlink'),
            'testBackground' => $this->context->getParameters()['testBackground'],
        ]);

        if ($this->getUser()->isLoggedIn() && $backlink !== null) {
            $this->restoreRequest($backlink);
        }

        try {
            if ($this->getUser()->isLoggedIn()) { //prodluzuje přihlášení při každém požadavku
                $this->userService->isLoggedIn();
            }
        } catch (AuthenticationException $e) {
            if ($this->getName() !== 'Auth' || $this->params['action'] !== 'skautisLogout') { //pokud jde o odhlaseni, tak to nevadi
                throw $e;
            }
        }
    }

    protected function beforeRender() : void
    {
        parent::beforeRender();

        $presenterNameParts = explode(':', $this->getName());

        $parameters = $this->context->getParameters();

        $this->template->setParameters([
            'module' => $presenterNameParts[1] ?? null,
            'presenterName' => $presenterNameParts[array_key_last($presenterNameParts)],
            'productionMode' => $parameters['productionMode'],
            'wwwDir' => $parameters['wwwDir'],
        ]);

        if (! $this->getUser()->isLoggedIn()) {
            return;
        }
    }

    public function handleChangeRole(?int $roleId = null) : void
    {
        if ($roleId === null) {
            throw new Nette\Application\BadRequestException();
        }

        $this['loginPanel']->handleChangeRole($roleId);
    }

    protected function createComponentLoginPanel() : LoginPanel
    {
        return $this->loginPanelFactory->create();
    }

    protected function createComponentVotingStateBox() : VotingStateBox
    {
        return $this->votingStateBoxFactory->create();
    }

    protected function createComponentTipBox() : TipBox
    {
        return $this->tipBoxFactory->create();
    }

    /**
     * @param IResponse $response
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    protected function shutdown($response) : void
    {
        foreach ($this->notificationsCollector->popNotifications() as [$type, $message, $count]) {
            if ($type === NotificationsCollector::ERROR) {
                $type = 'danger';
            }

            if ($count > 1) {
                $message = sprintf('%s (%d)', $message, $count);
            }

            $this->flashMessage($message, $type);
        }
    }
}
