<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Settings\TwoFactor;

use App\Livewire\Settings\TwoFactor\RecoveryCodes;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class RecoveryCodesTest extends AbstractIntegrationTestCase
{
    use MockeryPHPUnitIntegration;

    protected GenerateNewRecoveryCodes&MockInterface $generator;

    protected function setUp(): void
    {
        parent::setUp();

        config(['fortify.features' => [
            Features::twoFactorAuthentication([
                'confirm'         => true,
                'confirmPassword' => true,
            ]),
        ]]);

        $this->generator = Mockery::mock(GenerateNewRecoveryCodes::class);

        $this->app->instance(GenerateNewRecoveryCodes::class, $this->generator);
    }

    #[Test]
    public function it_loads_recovery_codes(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => 'xxx',
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        Livewire::test(RecoveryCodes::class)
            ->assertSet('recoveryCodes', ['yyy']);
    }

    #[Test]
    public function it_regenerates_recovery_codes(): void
    {
        $user = User::factory()->create([
            'two_factor_secret'         => 'xxx',
            'two_factor_confirmed_at'   => Carbon::now(),
            'two_factor_recovery_codes' => encrypt('["yyy"]'),
        ]);

        $this->actingAs($user);

        $this->generator
            ->shouldReceive('__invoke')
            ->once()
            ->with($user);

        Livewire::test(RecoveryCodes::class)
            ->call('regenerateRecoveryCodes')
            ->assertSet('recoveryCodes', ['yyy']);
    }
}
