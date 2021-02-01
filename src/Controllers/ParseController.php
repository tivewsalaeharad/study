<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ParseController extends Controller
{

    public function index(Request $request, Response $response)
    {
        $response->getBody()->write('<pre>' .print_library(

            'doctrine/inflector/lib/Doctrine/Inflector', '\\Doctrine\\Inflector'

        ) . '</pre>');
        return $response;
    }

}

//print_library('psr/log/Psr/Log', '\\Psr\\Log');
//print_library('psr/cache/src', '\\Psr\\Cache');
//print_library('psr/http-message/src', '\\Psr\\Http\\Message');
//print_library('psr/container/src', '\\Psr\\Container');
//print_library('psr/link/src', '\\Psr\\Link');
//print_library('psr/event-dispatcher/src', '\\Psr\\EventDispatcher');
//print_library('psr/http-server-handler/src', '\\Psr\\Http\\Server');
//print_library('psr/http-server-middleware/src', '\\Psr\\Http\\Server');
//print_library('psr/simple-cache/src', '\\Psr\\SimpleCache');
//print_library('psr/http-factory/src', '\\Psr\\Http\\Message');
//print_library('psr/http-client/src', '\\Psr\\Http\\Client');

//print_library('php-di/php-di/src', '\\DI');
//print_library('php-di/invoker/src', '\\Invoker');
//print_library('php-di/phpdoc-reader/src/PhpDocReader', '\\PhpDocReader');

//print_library('slim/slim/Slim', '\\Slim');
//print_library('slim/psr7/src', '\\Slim\\Psr7');




//print_library('illuminate/database', '\\Illuminate\\Database');
//print_library('phpunit/phpunit/src', '\\PHPUnit');
//print_library('doctrine/instantiator/src/Doctrine/Instantiator', '\\Doctrine\\Instantiator');