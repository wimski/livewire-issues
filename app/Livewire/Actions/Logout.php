<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use App\Enums\RouteNameEnum;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Features\SupportRedirects\Redirector as LivewireRedirector;

readonly class Logout
{
    public function __construct(
        protected AuthManager $auth,
        protected Session     $session,
        protected Redirector  $redirector,
    ) {
    }

    public function __invoke(): RedirectResponse|LivewireRedirector
    {
        $this->auth->logout();

        $this->session->invalidate();
        $this->session->regenerateToken();

        return $this->redirector->route(RouteNameEnum::HOME);
    }
}
