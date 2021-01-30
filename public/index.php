<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Framework\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';
$dispatcher = new EventDispatcher();

$request = Request::createFromGlobals();

$routes = require __DIR__.'/../src/routes.php';

$urlMatcher = new UrlMatcher($routes, new RequestContext());

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$dispatcher = new EventDispatcher();

$framework = new Framework\Simplex($dispatcher, $urlMatcher, new ControllerResolver(), new ArgumentResolver());
$response = $framework->handle($request);


$response->send();
