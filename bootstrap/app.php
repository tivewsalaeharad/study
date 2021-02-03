<?php

use App\Auth\Auth;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\HomeController;
use App\Controllers\ParseController;
use App\Middleware\CsrfViewMiddleware;
use App\Middleware\OldInputMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use DI\Container;
use Illuminate\Database\Capsule\Manager;
use Respect\Validation\Factory;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
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

$container->set('auth', fn() => new Auth);
$container->set('flash', fn() => new Messages());

$container->set('view', function () use ($container) {

    $view = Twig::create(__DIR__ . '/../resources/views', ['cache' => false]);

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->get('auth')->check(),
        'user' => $container->get('auth')->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->get('flash'));

    return $view;

});


$container->set('validator', fn() => new App\Validation\Validator);
$container->set('Home', fn() => new HomeController($container));
$container->set('Auth', fn() => new AuthController($container));
$container->set('Pass', fn() => new PasswordController($container));
$container->set('Parse', fn() => new ParseController($container));

$app = AppFactory::create();

$container->set('csrf', fn() => new Guard($app->getResponseFactory()));

$app->add(new ValidationErrorsMiddleware($container));
$app->add(new OldInputMiddleware($container));
$app->add(new CsrfViewMiddleware($container));
$app->add($container->get('csrf'));

Factory::setDefaultInstance(
    (new Factory)->withRuleNamespace('App\\Validation\\Rules')
);

$app->addMiddleware(TwigMiddleware::createFromContainer($app));

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->addErrorMiddleware(true, true, true);

return $app;