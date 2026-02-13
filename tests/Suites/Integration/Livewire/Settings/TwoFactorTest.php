<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Settings;

use App\Livewire\Settings\TwoFactor;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class TwoFactorTest extends AbstractIntegrationTestCase
{
    use MockeryPHPUnitIntegration;

    protected DisableTwoFactorAuthentication&MockInterface $disableTwoFactorAuthentication;
    protected EnableTwoFactorAuthentication&MockInterface  $enableTwoFactorAuthentication;
    protected ConfirmTwoFactorAuthentication&MockInterface $confirmTwoFactorAuthentication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableTwoFactorAuthentication = Mockery::mock(DisableTwoFactorAuthentication::class);
        $this->enableTwoFactorAuthentication  = Mockery::mock(EnableTwoFactorAuthentication::class);
        $this->confirmTwoFactorAuthentication = Mockery::mock(ConfirmTwoFactorAuthentication::class);

        $this->app->instance(DisableTwoFactorAuthentication::class, $this->disableTwoFactorAuthentication);
        $this->app->instance(EnableTwoFactorAuthentication::class, $this->enableTwoFactorAuthentication);
        $this->app->instance(ConfirmTwoFactorAuthentication::class, $this->confirmTwoFactorAuthentication);

        config(['fortify.features' => [
            Features::twoFactorAuthentication([
                'confirm'         => true,
                'confirmPassword' => true,
            ]),
        ]]);
    }

    #[Test]
    public function it_throws_an_exception_if_two_factor_authentication_is_disabled(): void
    {
        config(['fortify.features' => []]);

        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(TwoFactor::class)
            ->assertForbidden();
    }

    #[Test]
    public function it_mounts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->disableTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        $component = Livewire::test(TwoFactor::class);

        $component->assertSet('twoFactorEnabled', false);
        $component->assertSet('requiresConfirmation', true);
    }

    #[Test]
    public function it_enables(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        $this->disableTwoFactorAuthentication->shouldNotReceive('__invoke');
        $this->enableTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        $component = Livewire::test(TwoFactor::class);

        $component->assertSet('twoFactorEnabled', true);
        $component->call('enable');
        $component->assertSet('twoFactorEnabled', true);
        $component->assertSet('showModal', true);
        $component->assertSet('manualSetupKey', 'xxx');
        $component->assertNotSet('qrCodeSvg', '');
    }

    #[Test]
    public function it_shows_verification_if_necessary(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        Livewire::test(TwoFactor::class)
            ->call('showVerificationIfNecessary')
            ->assertSet('showVerificationStep', true);
    }

    #[Test]
    public function it_confirms_two_factor(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        $this->confirmTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user, '123456');

        Livewire::test(TwoFactor::class)
            ->set('code', '123456')
            ->call('confirmTwoFactor')
            ->assertSet('twoFactorEnabled', true);
    }

    #[Test]
    public function it_disables(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        $this->disableTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        $component = Livewire::test(TwoFactor::class);

        $component->assertSet('twoFactorEnabled', true);
        $component->call('disable');
        $component->assertSet('twoFactorEnabled', false);
    }

    #[Test]
    public function it_closes_a_modal(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        $component = Livewire::test(TwoFactor::class)
            ->set('code', 'a')
            ->set('showModal', true)
            ->set('showVerificationStep', true)
            ->call('closeModal');

        $component->assertSet('code', '');
        $component->assertSet('showModal', false);
        $component->assertSet('showVerificationStep', false);
    }

    #[Test]
    public function it_gets_modal_config_properties_when_two_factor_is_enabled(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => encrypt('xxx'),
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        Livewire::test(TwoFactor::class)
            ->call('getModalConfigProperty')
            ->assertReturned([
                'title'       => 'Two-Factor Authentication Enabled',
                'description' => 'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
                'buttonText'  => 'Close',
            ]);
    }

    #[Test]
    public function it_gets_modal_config_properties_when_shows_verification_step(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->disableTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        Livewire::test(TwoFactor::class)
            ->call('showVerificationIfNecessary')
            ->call('getModalConfigProperty')
            ->assertReturned([
                'title'       => 'Verify Authentication Code',
                'description' => 'Enter the 6-digit code from your authenticator app.',
                'buttonText'  => 'Continue',
            ]);
    }

    #[Test]
    public function it_gets_modal_config_properties_when_disabled(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->disableTwoFactorAuthentication
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        Livewire::test(TwoFactor::class)
            ->call('getModalConfigProperty')
            ->assertReturned([
                'title'       => 'Enable Two-Factor Authentication',
                'description' => 'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.',
                'buttonText'  => 'Continue',
            ]);
    }
}
