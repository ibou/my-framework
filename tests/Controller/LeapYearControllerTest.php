<?php


use Framework\Simplex;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class LeapYearControllerTest extends TestCase
{
    protected Simplex $framework;
    
    
    protected function setUp(): void
    {
        $routes = require __DIR__.'/../../src/routes.php';
        $urlMatcher = new UrlMatcher($routes, new RequestContext());
        $this->framework = new Framework\Simplex($urlMatcher, new ControllerResolver(), new ArgumentResolver());
    }
    
    public function testIsALeapYearDefault()
    {
        $request = Request::create('/is_leap_year');
        $response = $this->framework->handle($request);
        $this->assertStringContainsStringIgnoringCase('Nope, this is not a leap year.', $response->getContent());
    }
    
    public function testIsALeapYearWithParamsOk()
    {
        $request = Request::create('/is_leap_year/2000');
        $response = $this->framework->handle($request);
        $this->assertStringContainsStringIgnoringCase('Yep, this is a leap year!', $response->getContent());
    }
    public function testIsALeapYearWithParamKO()
    {
        $request = Request::create('/is_leap_year/2001');
        $response = $this->framework->handle($request);
        $this->assertStringContainsStringIgnoringCase('Nope, this is not a leap year.', $response->getContent());
    }
}