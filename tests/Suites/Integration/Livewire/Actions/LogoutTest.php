<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Actions;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class LogoutTest extends AbstractIntegrationTestCase
{
    protected Logout $action;
    protected AuthManager $auth;
    protected Session $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth    = $this->app->make(AuthManager::class);
        $this->session = $this->app->make(Session::class);

        $this->action = new Logout(
            $this->auth,
            $this->session,
            $this->app->make(Redirector::class),
        );
    }

    #[Test]
    public function it_logs_out_a_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        self::assertTrue($this->auth->check());

        $id    = $this->session->getId();
        $token = $this->session->token();

        $response = $this->action->__invoke();

        self::assertFalse($this->auth->check());
        self::assertNotEquals($id, $this->session->getId());
        self::assertNotEquals($token, $this->session->token());
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('http://test.app:8000', $response->getTargetUrl());
    }
}
