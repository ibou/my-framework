<?php


use Framework\Simplex;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class IndexTest extends TestCase
{
    protected Simplex $framework;
    
    
    protected function setUp(): void
    {
        $routes = require __DIR__.'/../src/routes.php';
        $urlMatcher = new UrlMatcher($routes, new RequestContext());
        $this->framework = new Framework\Simplex($urlMatcher, new ControllerResolver(), new ArgumentResolver());
    }
    
    
    public function testHello()
    {
        $request = Request::create("/hello/Ibou");
        $response = $this->framework->handle($request);
        $this->assertEquals('Hello Ibou', $response->getContent());
    
        $request = Request::create("/hello");
        $response = $this->framework->handle($request);
        $this->assertEquals('Hello World', $response->getContent());
    }
    
    public function testBye()
    {
        $request = Request::create("/bye");
        $response = $this->framework->handle($request);
        $this->assertEquals('Goodbye', $response->getContent());
    }
    
    public function testAbout()
    {
        $request = Request::create("/a-propos");
        $response = $this->framework->handle($request);
        $this->assertStringContainsStringIgnoringCase('Une page about est générée, cool !', $response->getContent());
    }
}