<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name(RouteNameEnum::HOME);

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', 'settings/profile');
    Route::livewire('settings/profile', Profile::class)->name(RouteNameEnum::PROFILE_EDIT);
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::livewire('settings/password', Password::class)->name(RouteNameEnum::USER_PASSWORD_EDIT);
    Route::livewire('settings/appearance', Appearance::class)->name(RouteNameEnum::APPEARANCE_EDIT);
    Route::livewire('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name(RouteNameEnum::TWO_FACTOR_SHOW);
});
