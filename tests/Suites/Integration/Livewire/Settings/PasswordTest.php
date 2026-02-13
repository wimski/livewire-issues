<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Settings;

use App\Livewire\Settings\Password;
use App\Models\User;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Livewire;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class PasswordTest extends AbstractIntegrationTestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_updates_a_password(): void
    {
        $user = User::factory()->create(['password' => 'xxx']);

        $this->actingAs($user);

        $updater = Mockery::mock(UpdatesUserPasswords::class);
        $updater->shouldReceive('update')->once()->with($user, [
            'current_password'      => 'xxx',
            'password'              => 'yyy',
            'password_confirmation' => 'zzz',
        ]);

        $this->app->instance(UpdatesUserPasswords::class, $updater);

        $component = Livewire::test(Password::class)
            ->set('current_password', 'xxx')
            ->set('password', 'yyy')
            ->set('password_confirmation', 'zzz')
            ->call('updatePassword');

        $component->assertSet('current_password', '');
        $component->assertSet('password', '');
        $component->assertSet('password_confirmation', '');
        $component->assertDispatched('password-updated');
    }
}
