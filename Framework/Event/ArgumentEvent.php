<?php


namespace Framework\Event;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class ArgumentEvent extends EventDispatcher
{
    protected Request $request;
    protected $controller;
    protected array $arguments;
    
    public function __construct(Request $request, callable $controller, array $arguments)
    {
        $this->request = $request;
        $this->controller = $controller;
        $this->arguments = $arguments;
    }
 
    public function getRequest(): Request
    {
        return $this->request;
    }
    
 
    public function getController(): callable
    {
        return $this->controller;
    }
    
 
    public function getArguments(): array
    {
        return $this->arguments;
    }
    
    

}