<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke(Request $request, Handler $handler) : Response
    {
        $this->container->get('view')->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        unset($_SESSION['errors']);
        return $handler->handle($request);
    }
}