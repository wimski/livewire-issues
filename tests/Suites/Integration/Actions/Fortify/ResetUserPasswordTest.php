<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Actions\Fortify;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class ResetUserPasswordTest extends AbstractIntegrationTestCase
{
    protected ResetUserPassword $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ResetUserPassword(
            $this->app->make(ValidatorFactory::class),
        );
    }

    #[Test]
    public function it_saves_a_new_password(): void
    {
        $user = User::factory()->create(['password' => 'xxx']);

        $this->action->reset($user, [
            'password'              => 'yyyyyyyyy',
            'password_confirmation' => 'yyyyyyyyy',
        ]);

        self::assertTrue(Hash::check('yyyyyyyyy', $user->password));
    }
}
