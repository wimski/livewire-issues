<?php

declare(strict_types=1);

namespace App\Enums;

use Wimski\LaravelUtils\Concerns\AddsParamsToValidationRule;
use Wimski\LaravelUtils\Contracts\ValidationRuleIdentifierInterface;

enum ValidationRuleEnum: string implements ValidationRuleIdentifierInterface
{
    use AddsParamsToValidationRule;

    public function getValue(): string
    {
        return $this->value;
    }

    protected function getIdentifier(): ValidationRuleIdentifierInterface
    {
        return $this;
    }
}
