<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Actions\Fortify;

use App\Actions\Fortify\CreateNewUser;
use App\Contracts\Factories\Models\UserFactoryInterface;
use App\Models\User;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class CreateNewUserTest extends AbstractIntegrationTestCase
{
    protected CreateNewUser $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new CreateNewUser(
            $this->app->make(ValidatorFactory::class),
            $this->app->make(UserFactoryInterface::class),
        );
    }

    #[Test]
    public function it_creates_a_new_user(): void
    {
        $user = $this->action->create([
            'name'                  => 'a',
            'email'                 => 'b@b.b',
            'password'              => 'cccccccc',
            'password_confirmation' => 'cccccccc',
        ]);

        self::assertInstanceOf(User::class, $user);
        self::assertTrue($user->exists);
        self::assertSame('a', $user->name);
        self::assertSame('b@b.b', $user->email);
        self::assertTrue(Hash::check('cccccccc', $user->password));
    }
}
