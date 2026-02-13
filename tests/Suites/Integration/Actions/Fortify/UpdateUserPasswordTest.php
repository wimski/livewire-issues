<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Actions\Fortify;

use App\Actions\Fortify\UpdateUserPassword;
use App\Models\User;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class UpdateUserPasswordTest extends AbstractIntegrationTestCase
{
    protected UpdateUserPassword $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new UpdateUserPassword(
            $this->app->make(ValidatorFactory::class),
            $this->app->make(Translator::class),
        );
    }

    #[Test]
    public function it_updates_a_users_password(): void
    {
        $user = User::factory()->create(['password' => 'xxx']);

        $this->actingAs($user);

        $this->action->update($user, [
            'current_password'      => 'xxx',
            'password'              => 'yyyyyyyyy',
            'password_confirmation' => 'yyyyyyyyy',
        ]);

        self::assertTrue(Hash::check('yyyyyyyyy', $user->password));
    }
}
