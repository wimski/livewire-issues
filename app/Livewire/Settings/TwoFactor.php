<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Concerns\ValidatesUserClass;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Translation\Translator;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

class TwoFactor extends Component
{
    use ValidatesUserClass;

    protected DisableTwoFactorAuthentication $disableTwoFactorAuthentication;
    protected EnableTwoFactorAuthentication  $enableTwoFactorAuthentication;
    protected ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication;
    protected Translator                     $translator;
    protected User                           $user;

    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool   $showModal            = false;
    public bool   $showVerificationStep = false;
    public string $code                 = '';

    public function boot(
        AuthManager                    $auth,
        DisableTwoFactorAuthentication $disableTwoFactorAuthentication,
        EnableTwoFactorAuthentication  $enableTwoFactorAuthentication,
        ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication,
        Translator                     $translator,
    ): void {
        $this->disableTwoFactorAuthentication = $disableTwoFactorAuthentication;
        $this->enableTwoFactorAuthentication  = $enableTwoFactorAuthentication;
        $this->confirmTwoFactorAuthentication = $confirmTwoFactorAuthentication;
        $this->translator                     = $translator;
        $this->user                           = $this->validateUserClass($auth->user());
    }

    public function mount(): void
    {
        if (! Features::enabled(Features::twoFactorAuthentication())) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        if (Fortify::confirmsTwoFactorAuthentication() && ! $this->user->two_factor_confirmed_at) {
            $this->disableTwoFactorAuthentication->__invoke($this->user);
        }

        $this->twoFactorEnabled     = $this->user->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    public function enable(): void
    {
        $this->enableTwoFactorAuthentication->__invoke($this->user);

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
    }

    protected function loadSetupData(): void
    {
        try {
            $twoFactorSecret = $this->user->two_factor_secret;

            if (! is_string($twoFactorSecret)) {
                throw new RuntimeException();
            }

            $decryptedValue = decrypt($twoFactorSecret);

            if (! is_string($decryptedValue)) {
                throw new RuntimeException();
            }

            $this->qrCodeSvg      = $this->user->twoFactorQrCodeSvg();
            $this->manualSetupKey = $decryptedValue;
        } catch (Throwable) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
    }

    public function confirmTwoFactor(): void
    {
        $this->validate([
            'code' => [
                ValidationRuleMaker::required(),
                ValidationRuleMaker::string(),
                ValidationRuleMaker::size(6),
            ],
        ]);

        $this->confirmTwoFactorAuthentication->__invoke($this->user, $this->code);

        $this->closeModal();

        $this->twoFactorEnabled = true;
    }

    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    public function disable(): void
    {
        $this->disableTwoFactorAuthentication->__invoke($this->user);

        $this->twoFactorEnabled = false;
    }

    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
        }
    }

    /**
     * @return array{title: string, description: string, buttonText: string}
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title'       => $this->translator->get('Two-Factor Authentication Enabled'),
                'description' => $this->translator->get('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText'  => $this->translator->get('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title'       => $this->translator->get('Verify Authentication Code'),
                'description' => $this->translator->get('Enter the 6-digit code from your authenticator app.'),
                'buttonText'  => $this->translator->get('Continue'),
            ];
        }

        return [
            'title'       => $this->translator->get('Enable Two-Factor Authentication'),
            'description' => $this->translator->get('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText'  => $this->translator->get('Continue'),
        ];
    }
}
