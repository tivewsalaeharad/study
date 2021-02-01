<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class OldInputMiddleware extends Middleware
{

    public function __invoke(Request $request, Handler $handler) : Response
    {
        $this->container->get('view')->getEnvironment()->addGlobal('old', $_SESSION['old']);
        $_SESSION['old'] = $request->getParsedBody();
        return $handler->handle($request);
    }

}