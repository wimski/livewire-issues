<?php

declare(strict_types=1);

namespace App\Concerns\Fortify;

use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

trait NameValidationRules
{
    /**
     * @return list<string>
     */
    protected function nameRules(): array
    {
        return [
            ValidationRuleMaker::required(),
            ValidationRuleMaker::string(),
            ValidationRuleMaker::max(255),
        ];
    }
}
