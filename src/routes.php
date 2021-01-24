<?php

use App\Controller\GreatingController;
use App\Controller\PageController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add(
    'hello',
    new Route(
        '/hello/{name}', [
                           'name' => 'World',
                           '_controller' => [new GreatingController, 'hello'],
                       ]
    )
);
$routes->add(
    'bye',
    new Route(
        '/bye',
        [
            '_controller' => [new GreatingController, 'bye'],
        ]
    )
);
$routes->add(
    'cms/about',
    new Route(
        '/a-propos',
        [
            '_controller' => [new PageController, 'about'],
        ]
    )
);

return $routes;