<?php


namespace Framework\Event;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class ControllerEvent extends EventDispatcher
{
    protected Request $request;
    
    protected $controller;
    
    public function __construct(Request $request, callable $controller)
    {
        $this->request = $request;
        $this->controller = $controller;
    }
    
    public function getController(): callable
    {
        return $this->controller;
    }
    
    public function getRequest(): Request
    {
        return $this->request;
    }
}