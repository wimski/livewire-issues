<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\RateLimiterEnum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootActions();
        $this->bootViews();
        $this->bootRateLimiters();
    }

    protected function bootActions(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
    }

    protected function bootViews(): void
    {
        Fortify::confirmPasswordView(fn (): View => view('livewire.auth.confirm-password'));
        Fortify::loginView(fn (): View => view('livewire.auth.login'));
        Fortify::registerView(fn (): View => view('livewire.auth.register'));
        Fortify::requestPasswordResetLinkView(fn (): View => view('livewire.auth.forgot-password'));
        Fortify::resetPasswordView(fn (): View => view('livewire.auth.reset-password'));
        Fortify::twoFactorChallengeView(fn (): View => view('livewire.auth.two-factor-challenge'));
        Fortify::verifyEmailView(fn (): View => view('livewire.auth.verify-email'));
    }

    protected function bootRateLimiters(): void
    {
        RateLimiter::for(RateLimiterEnum::TWO_FACTOR, function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
