<?php

namespace App\Concerns\Fortify;

use Illuminate\Validation\Rules\Password;
use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

trait PasswordValidationRules
{
    /**
     * @return list<Password|string>
     */
    protected function passwordRules(): array
    {
        return [
            ValidationRuleMaker::required(),
            ValidationRuleMaker::string(),
            Password::default(),
            ValidationRuleMaker::confirmed(),
        ];
    }

    /**
     * @return list<string>
     */
    protected function currentPasswordRules(): array
    {
        return [
            ValidationRuleMaker::required(),
            ValidationRuleMaker::string(),
            ValidationRuleMaker::currentPassword('web'),
        ];
    }
}
