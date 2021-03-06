<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class EmailAvailableException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Данный адрес электронной почты уже занят.'
        ]
    ];
}
