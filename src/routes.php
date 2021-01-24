<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add(
    'hello',
    new Route(
        '/hello/{name}', [
                           'name' => 'World',
                           '_controller' => 'App\Controller\GreatingController::hello',
                       ]
    )
);
$routes->add(
    'bye',
    new Route(
        '/bye',
        [
            '_controller' => 'App\Controller\GreatingController::bye',
        ]
    )
);
$routes->add(
    'cms/about',
    new Route(
        '/a-propos',
        [
            '_controller' => 'App\Controller\PageController::about',
        ]
    )
);

$routes->add(
    'leap_year',
    new Route(
        '/is_leap_year/{year}',
        [
            '_controller' => 'App\Controller\LeapYearController::index',
            'year' => null,
            'message' => '',
        ]
    )
);

return $routes;