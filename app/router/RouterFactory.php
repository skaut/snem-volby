<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

/**
 * Router factory.
 */
class RouterFactory
{
    public function createRouter() : Router
    {
        $router = new RouteList();

        $router->addRoute(
            'sign/<action>[/back-<backlink>]',
            [
                'presenter' => 'Auth',
                'action' => 'default',
                'backlink' => null,
            ]
        );

        $router->addRoute('/', 'Homepage:default');
        $router->addRoute('auth/<presenter>/<action>', ['module' => 'Authenticated']);

        return $router;
    }
}
