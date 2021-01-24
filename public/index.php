<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = require __DIR__.'/../src/routes.php';

$urlMatcher = new UrlMatcher($routes, new RequestContext());

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework\Simplex($urlMatcher, new ControllerResolver(), new ArgumentResolver());
$response = $framework->handle($request);

$response->send();
