<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Settings;

use App\Livewire\Settings\Profile;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Livewire;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class ProfileTest extends AbstractIntegrationTestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_updates_profile_information(): void
    {
        $user = User::factory()->create([
            'name'  => 'foo',
            'email' => 'x@y.z',
        ]);

        $this->actingAs($user);

        $updater = Mockery::mock(UpdatesUserProfileInformation::class);
        $updater->shouldReceive('update')->once()->with($user, [
            'name'  => 'bar',
            'email' => 'a@b.c',
        ]);

        $this->app->instance(UpdatesUserProfileInformation::class, $updater);

        Livewire::test(Profile::class)
            ->set('name', 'bar')
            ->set('email', 'a@b.c')
            ->call('updateProfileInformation')
            ->assertDispatched('profile-updated', name: 'foo');
    }

    #[Test]
    public function it_resends_a_verification_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->call('resendVerificationNotification')
            ->assertSessionHas('_flash.new', ['status']);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function it_redirects_home_if_the_user_has_a_verified_email(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => CarbonImmutable::now(),
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->call('resendVerificationNotification')
            ->assertRedirect('http://test.app:8000');
    }

    #[Test]
    public function it_returns_if_the_user_has_an_unverified_email(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->assertSet('hasUnverifiedEmail', true);
    }

    #[Test]
    public function it_returns_if_it_shows_the_delete_user_option(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => CarbonImmutable::now(),
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->assertSet('showDeleteUser', true);
    }
}
