<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as Respect;

class AuthController extends Controller
{

    public function getSignUp(Request $request, Response $response)
    {
        //var_dump($request->getAttribute('csrf_value'));
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => Respect::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => Respect::notEmpty()->alpha(),
            'password' => Respect::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()) {
            return $response->withHeader('Location','/auth/signup');
        }

        $params = $request->getParsedBody();

        $user = User::create([
            'email' => $params['email'],
            'name' => $params['name'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => 10]),
        ]);

        return $response->withHeader('Location','/');
    }
    
}
