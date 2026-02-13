<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Concerns\ValidatesUserClass;
use Illuminate\Auth\AuthManager;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Component;

class Password extends Component
{
    use ValidatesUserClass;

    protected AuthManager          $auth;
    protected UpdatesUserPasswords $passwordUpdater;

    public string $current_password      = '';
    public string $password              = '';
    public string $password_confirmation = '';

    public function boot(
        AuthManager          $auth,
        UpdatesUserPasswords $passwordUpdater,
    ): void {
        $this->auth            = $auth;
        $this->passwordUpdater = $passwordUpdater;
    }

    public function updatePassword(): void
    {
        $user = $this->validateUserClass($this->auth->user());

        try {
            $this->passwordUpdater->update($user, [
                'current_password'      => $this->current_password,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ]);
        } catch (ValidationException $exception) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $exception;
        }

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}
