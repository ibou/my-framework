<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = require __DIR__.'/../src/routes.php';

$context = new RequestContext();
$context->fromRequest($request);
$urlMatcher = new UrlMatcher($routes, $context);
try {
    $request->attributes->add($urlMatcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (ResourceNotFoundException $exception) {
    $response = new Response('La page nexiste pas', 404);
} catch (\Exception $exception) {
    $response = new Response('Une error est arrivée', 500);
}


$response->send();
