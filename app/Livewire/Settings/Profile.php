<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Concerns\ValidatesUserClass;
use App\Enums\RouteNameEnum;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use ValidatesUserClass;

    protected UpdatesUserProfileInformation $profileUpdater;
    protected UrlGenerator                  $urlGenerator;
    protected Session                       $session;
    protected User                          $user;

    public string $name  = '';
    public string $email = '';

    public function boot(
        AuthManager                   $auth,
        UpdatesUserProfileInformation $profileUpdater,
        UrlGenerator                  $urlGenerator,
        Session                       $session,
    ): void {
        $this->profileUpdater = $profileUpdater;
        $this->urlGenerator   = $urlGenerator;
        $this->session        = $session;
        $this->user           = $this->validateUserClass($auth->user());
    }

    public function mount(): void
    {
        $this->name  = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfileInformation(): void
    {
        $this->profileUpdater->update($this->user, [
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('profile-updated', name: $this->user->name);
    }

    public function resendVerificationNotification(): void
    {
        if ($this->user->hasVerifiedEmail()) {
            $this->redirectIntended($this->urlGenerator->route(RouteNameEnum::HOME->value, absolute: false));

            return;
        }

        $this->user->sendEmailVerificationNotification();

        $this->session->flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return ! $this->user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return $this->user->hasVerifiedEmail();
    }
}
