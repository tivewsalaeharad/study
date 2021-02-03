<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GuestMiddleware extends Middleware
{
    public function __invoke(Request $request, Handler $handler) : Response
    {
        if ($this->container->get('auth')->check()) {
            return (new \Slim\Psr7\Response)->withHeader('Location','/');
        }
        return $handler->handle($request);
    }
}