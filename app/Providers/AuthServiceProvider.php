<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Password::defaults(fn (): ?Password => $this->app->isProduction() ? Password::min(12)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols()
            ->uncompromised() : null);
    }
}
