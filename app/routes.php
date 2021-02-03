<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->get('/', 'Home:index')->setName('home');
    $app->get('/site/{subdivision}', 'Home:index')->setName('home');

    $app->get('/parse', 'Parse:index');

    $app->group('', function (RouteCollectorProxy $group){

        $group->get('/auth/signup', 'Auth:getSignUp')->setName('auth.signup');
        $group->post('/auth/signup', 'Auth:postSignUp');

        $group->get('/auth/signin', 'Auth:getSignIn')->setName('auth.signin');
        $group->post('/auth/signin', 'Auth:postSignIn');

    })->add(new GuestMiddleware($app->getContainer()));

    $app->group('', function (RouteCollectorProxy $group){

        $group->get('/auth/signout', 'Auth:getSignOut')->setName('auth.signout');

        $group->get('/auth/password/change', 'Pass:getChangePassword')->setName('auth.password.change');
        $group->post('/auth/password/change', 'Pass:postChangePassword');

    })->add(new AuthMiddleware($app->getContainer()));


};
