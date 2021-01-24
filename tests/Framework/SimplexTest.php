<?php


use App\Controller\LeapYearController;
use Framework\Simplex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class SimplexTest extends \PHPUnit\Framework\TestCase
{
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());
        $response = $framework->handle(new Request());
        
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    private function getFrameworkForException($exception): Simplex
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);
        
        $controllerResolver = $this->createMock(ControllerResolver::class);
        
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);
        
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception));
        
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)));
        
        return new Simplex($matcher, $controllerResolver, $argumentResolver);
    }
    
    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \Exception());
        $response = $framework->handle(new Request());
        $this->assertEquals(500, $response->getStatusCode());
    }
    
    public function testControllerResponse()
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);
        
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will(
                $this->returnValue(
                    [
                        '_route' => 'is_leap_year/{year}',
                        '_controller' => [new LeapYearController(), 'index'],
                        'year' => 2000,
                        'message' => '',
                    ]
                )
            );
        
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)));
        
        $framework = new Simplex($matcher, new ControllerResolver(), new ArgumentResolver());
        
        $request = Request::create('/is_leap_year');
        $response = $framework->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Yep, this is a leap year!', $response->getContent());
    }
}