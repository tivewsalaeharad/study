<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ParseController;
use App\Middleware\OldInputMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use DI\Container;
use Illuminate\Database\Capsule\Manager;
use Respect\Validation\Factory;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = new Container;
AppFactory::setContainer($container);

$db_settings = require __DIR__ . '/../settings/db.php';
$capsule = new Manager;
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->addConnection($db_settings);

$container->set('view', function() {
    return Twig::create(__DIR__ . '/../resources/views', ['cache' => false]);
});

$container->set('validator', function() use ($container) {
    return new App\Validation\Validator;
});

$container->set('Home', function() use ($container) {
    return new HomeController($container);
});

$container->set('Auth', function() use ($container) {
    return new AuthController($container);
});

$container->set('Parse', function() use ($container) {
    return new ParseController($container);
});

$app = AppFactory::create();

$container->set('csrf', function() use($container, $app) {
    return new Guard($app->getResponseFactory());
});

$app->add(new ValidationErrorsMiddleware($container));
$app->add(new OldInputMiddleware($container));
$app->add($container->get('csrf'));

Factory::setDefaultInstance(
    (new Factory)->withRuleNamespace('App\\Validation\\Rules')
);

$app->addMiddleware(TwigMiddleware::createFromContainer($app));

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->addErrorMiddleware(true, true, true);

return $app;