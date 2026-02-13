<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Concerns\Fortify\PasswordValidationRules;
use App\Concerns\ValidatesUserClass;
use App\Enums\RouteNameEnum;
use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;
use Livewire\Component;

class DeleteUserForm extends Component
{
    use PasswordValidationRules;
    use ValidatesUserClass;

    protected Logout       $logout;
    protected UrlGenerator $urlGenerator;
    protected User         $user;

    public string $password = '';

    public function boot(
        AuthManager  $auth,
        Logout       $logout,
        UrlGenerator $urlGenerator,
    ): void {
        $this->logout       = $logout;
        $this->urlGenerator = $urlGenerator;
        $this->user         = $this->validateUserClass($auth->user());
    }

    public function deleteUser(): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        $this->logout->__invoke();

        $this->user->delete();

        $this->redirect($this->urlGenerator->route(RouteNameEnum::HOME->value), true);
    }
}
