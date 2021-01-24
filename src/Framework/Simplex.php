<?php

namespace Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Simplex
{
    private UrlMatcherInterface $matcher;
    private ControllerResolverInterface $controllerResolver;
    private ArgumentResolverInterface $argumentResolver;
    
    /**
     * Simplex constructor.
     * @param UrlMatcherInterface $matcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(
        UrlMatcherInterface $matcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }
    
    
    public function handle(Request $request): Response
    {
        $this->matcher->getContext()->fromRequest($request);
        
        try {
            
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            
            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            return new Response('La page nexiste pas', 404);
        } catch (\Exception $exception) {
            return new Response('Une error est arrivée', 500);
        }
    }
}