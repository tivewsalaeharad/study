<?php

use Slim\App;

return function (App $app) {

    $app->get('/', 'Home:index')->setName('home');

    $app->get('/parse', 'Parse:index');

    $app->get('/auth/signup', 'Auth:getSignUp')->setName('auth.signup');
    $app->post('/auth/signup', 'Auth:postSignUp');

    //Example Vue.js
    $app->get('/register', 'Home:register');
};
