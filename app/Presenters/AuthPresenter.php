<?php

declare(strict_types=1);

namespace App;

use eGen\MessageBus\Bus\CommandBus;
use Model\AuthService;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\DelegateNotFound;
use Model\User\Commands\SaveFirstLogin;
use Model\User\Exception\UserHasNoRole;
use Model\UserService;
use Model\Vote\VotingTime;
use Nette\Security\Identity;
use Skautis\Wsdl\AuthenticationException;
use function assert;
use function sprintf;
use function strlen;
use function substr;

class AuthPresenter extends BasePresenter
{
    protected AuthService $authService;

    protected CommandBus $commandBus;

    public function __construct(AuthService $as, CommandBus $commandBus)
    {
        parent::__construct();
        $this->authService = $as;
        $this->commandBus  = $commandBus;
    }

    /**
     * pokud je uziatel uz prihlasen, staci udelat referesh
     */
    public function actionDefault(?string $backlink = null) : void
    {
        $this->redirect(':Authenticated:Default:');
    }

    /**
     * přesměruje na stránku s přihlášením
     */
    public function actionLogOnSkautIs(?string $backlink = null) : void
    {
        if ($backlink !== null) {
            $backlink = $this->getHttpRequest()->getUrl()->getBaseUrl() . $backlink;
        }
        $this->redirectUrl($this->authService->getLoginUrl($backlink));
    }

    /**
     * zajistuje zpracovani prihlaseni na skautIS
     */
    public function actionSkautIS(?string $ReturnUrl = null) : void
    {
        $post = $this->getRequest()->getPost();
        if (! isset($post['skautIS_Token'])) { //pokud není nastavený token, tak zde nemá co dělat
            $this->redirect(':Authenticated:Default:');
        }
        try {
            $this->authService->setInit(
                $post['skautIS_Token'],
                (int) $post['skautIS_IDRole'],
                (int) $post['skautIS_IDUnit']
            );

            if (! $this->userService->isLoggedIn(false)) {
                throw new AuthenticationException('Nemáte platné přihlášení do skautisu');
            }

            $user = $this->getUser();

            $user->setExpiration('+ 29 minutes'); // nastavíme expiraci
            $roles = $this->userService->getRelatedSkautisRoles();
            if (empty($roles)) {
                throw new AuthenticationException('Nemáte roli delegáta Sněmu, proto se nemůžete přihlásit! Hlasovat mohou jen řádní delegáti. Náhradníci ani ostatní osoby hlasovat nemohou.');
            }
            $user->login(new Identity(
                $this->userService->getUserDetail()->ID,
                $roles,
            ));

            $votingTime = $this->queryBus->handle(new VotingTimeQuery());
            assert($votingTime instanceof VotingTime);

            if (! $votingTime->isVotingInProgress() && ! $this->userService->canBeAdmin()) {
                $this->user->logout();
                $this->flashMessage(sprintf('K účasti v elektronických volbách se lze přihlásit až v jejich termínu (%s - %s). Vraťte se na tento web až v termínu voleb!', $votingTime->getBegin()->format('j. n. Y G:i'), $votingTime->getEnd()->format('j. n. Y G:i')), 'danger');
                $this->redirect(':Homepage:');
            }

            try {
                $this->commandBus->handle(new SaveFirstLogin($this->userService->getUserPersonId()));
            } catch (DelegateNotFound $exc) {
            }

            $this->setupDefaultRole();

            if ($ReturnUrl !== null) {
                $this->restoreRequest(substr($ReturnUrl, strlen($this->getHttpRequest()->getUrl()->getBaseUrl())));
            }
        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), 'danger');
            $this->redirect(':Auth:');
        }
        $this->redirect(':Authenticated:Default:');
    }

    private function setupDefaultRole() : void
    {
        $roles          = $this->userService->getRelatedSkautisRoles();
        $superadminRole = null;
        foreach ($roles as $role) {
            if ($role->Key === UserService::ROLE_KEY_DELEGATE) {
                $this->handleChangeRole($role->ID);

                return;
            }
            if ($role->Key !== UserService::ROLE_KEY_SUPERADMIN) {
                continue;
            }

            $superadminRole = $role;
        }
        if ($superadminRole !== null) {
            $this->handleChangeRole($superadminRole->ID);

            return;
        }
        throw new UserHasNoRole("User doesn't have allowed role!");
    }

    public function actionAjax(?string $backlink = null) : void
    {
        $this->template->setParameters(['backlink' => $backlink]);
        $this->flashMessage('Vypršel čas přihlášení. Přihlaste se prosím znovu.', 'warning');
        $this->redrawControl();
    }

    /**
     * zajištuje odhlašení ze skautisu
     * Skautis po svém odhlášení přesměruje na actionSkautisLogout
     */
    public function actionLogoutSIS() : void
    {
        $this->redirectUrl($this->authService->getLogoutUrl());
    }

    /**
     * slouží pouze jako návratová adresa po odhlášení ze skautisu
     */
    public function actionSkautisLogout() : void
    {
        $this->getUser()->logout(true);
        if (isset($this->getRequest()->getPost()['skautIS_Logout'])) {
            $this->flashMessage('Odhlášení proběhlo úspěšně.');
        } else {
            $this->flashMessage('Odhlášení ze skautisu se nezdařilo', 'danger');
        }
        $this->redirect(':Homepage:');
    }
}
