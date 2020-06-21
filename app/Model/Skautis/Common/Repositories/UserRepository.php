<?php

declare(strict_types=1);

namespace Model\Skautis\Common\Repositories;

use Model\Common\Repositories\IUserRepository;
use Model\Common\User;
use Model\Common\UserNotFound;
use Skautis\Skautis;
use Skautis\Wsdl\Decorator\Cache\ArrayCache;
use Skautis\Wsdl\Decorator\Cache\CacheDecorator;
use Skautis\Wsdl\PermissionException;
use Skautis\Wsdl\WebServiceInterface;
use stdClass;

final class UserRepository implements IUserRepository
{
    private WebServiceInterface $userWebService;

    private WebServiceInterface $orgWebService;

    public function __construct(Skautis $skautis)
    {
        $this->userWebService = new CacheDecorator($skautis->getWebService('user'), new ArrayCache());
        $this->orgWebService  = new CacheDecorator($skautis->getWebService('org'), new ArrayCache());
    }

    public function getCurrentUser() : User
    {
        try {
            $user = $this->userWebService->UserDetail([]);
            if ($user instanceof stdClass) {
                $person = $this->orgWebService->PersonDetail([]);

                return new User($user->ID, $user->Person, $person->Email);
            }
        } catch (PermissionException $e) {
            throw UserNotFound::fromPrevious($e);
        }

        throw new UserNotFound('User was not found');
    }
}
