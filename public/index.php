<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$path = $request->getPathInfo();

$map = [
    '/hello' => __DIR__.'/../src/pages/hello.php',
    '/bye' => __DIR__.'/../src/pages/bye.php',
    '/a-propos' => __DIR__.'/../src/pages/cms/about.php',
];
$response = new Response();

$path = $request->getPathInfo();
if (isset($map[$path])) {
    ob_start();
    include $map[$path];
    $response->setContent(ob_get_clean());
} else {
    $response->setStatusCode(404);
    $response->setContent('La page nexiste pas');
}

$response->send();
