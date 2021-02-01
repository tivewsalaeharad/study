<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;

class HomeController extends Controller
{

    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'home.twig');
    }

    public function register(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'register.twig');
    }


}