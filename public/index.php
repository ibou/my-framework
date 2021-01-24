<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = require __DIR__.'/../src/routes.php';

$context = new RequestContext();
$context->fromRequest($request);
$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

try {
    $request->attributes->add($urlMatcher->match($request->getPathInfo()));
    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);
    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $exception) {
    $response = new Response('La page nexiste pas', 404);
} catch (\Exception $exception) {
    $response = new Response('Une error est arrivée', 500);
}


$response->send();
