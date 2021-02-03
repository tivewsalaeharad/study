<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as Respect;

class PasswordController extends Controller
{
    public function getChangePassword(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    public function postChangePassword(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $validation = $this->validator->validate($request, [
            'password_old' => Respect::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => Respect::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withHeader('Location','/auth/password/change');
        }

        $this->auth->user()->setPassword($params['password']);

        $this->flash->addMessage('info', 'Ваш пароль был изменён');

        return $response->withHeader('Location','/');

    }

}
