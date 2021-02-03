<?php

namespace App\Validation;

use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected array $errors = [];

    public function validate(Request $request, array $rules)
    {
        $params = $request->getParsedBody();

        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($params[$field]);
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}