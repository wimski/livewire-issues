<?php

declare(strict_types=1);

namespace App\Concerns\Fortify;

use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

trait EmailValidationRules
{
    /**
     * @return list<string>
     */
    protected function emailRules(): array
    {
        return [
            ValidationRuleMaker::required(),
            ValidationRuleMaker::string(),
            ValidationRuleMaker::email(),
            ValidationRuleMaker::max(255),
        ];
    }
}
