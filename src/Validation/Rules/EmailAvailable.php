<?php

namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{

    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        return User::where('email', $input)->count() === 0;
    }
}