<?php

declare(strict_types=1);

namespace App\Livewire\Settings\TwoFactor;

use App\Concerns\ValidatesUserClass;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;
use Throwable;

class RecoveryCodes extends Component
{
    use ValidatesUserClass;

    protected GenerateNewRecoveryCodes $recoveryCodesGenerator;
    protected User                     $user;

    /**
     * @var list<string>
     */
    #[Locked]
    public array $recoveryCodes = [];

    public function boot(
        AuthManager              $auth,
        GenerateNewRecoveryCodes $recoveryCodesGenerator,
    ): void {
        $this->recoveryCodesGenerator = $recoveryCodesGenerator;
        $this->user                   = $this->validateUserClass($auth->user());
    }

    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->recoveryCodesGenerator->__invoke($this->user);

        $this->loadRecoveryCodes();
    }

    protected function loadRecoveryCodes(): void
    {
        if (
            ! $this->user->hasEnabledTwoFactorAuthentication() ||
            ! $this->user->two_factor_recovery_codes
        ) {
            return;
        }

        try {
            $decryptedValue = decrypt($this->user->two_factor_recovery_codes);

            if (! is_string($decryptedValue)) {
                throw new RuntimeException();
            }

            /** @var list<string> $data */
            $data = json_decode($decryptedValue, true);

            $this->recoveryCodes = $data;
        } catch (Throwable) {
            $this->addError('recoveryCodes', 'Failed to load recovery codes');

            $this->recoveryCodes = [];
        }
    }
}
