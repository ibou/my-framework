<?php

namespace Framework;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;

;

class Simplex
{
    protected EventDispatcherInterface $dispatcher;
    protected UrlMatcherInterface $matcher;
    protected ControllerResolverInterface $controllerResolver;
    protected ArgumentResolverInterface $argumentResolver;
    
    /**
     * Simplex constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param UrlMatcherInterface $matcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        UrlMatcherInterface $matcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }
    
    
    public function handle(Request $request): Response
    {
        $this->matcher->getContext()->fromRequest($request);
        
        $this->dispatcher->dispatch(new Event(), 'kernel.request');
        
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $this->dispatcher->dispatch(new Event(), 'kernel.controller');
            $controller = $this->controllerResolver->getController($request);
            $this->dispatcher->dispatch(new Event(), 'kernel.arguments');
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            
            $response = call_user_func_array($controller, $arguments);
            $this->dispatcher->dispatch(new Event(), 'kernel.response');
            return $response;
        } catch (ResourceNotFoundException $exception) {
            return new Response('La page nexiste pas', 404);
        } catch (\Exception $exception) {
            return new Response('Une error est arrivée', 500);
        }
    }
}