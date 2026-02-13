<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Factories\Models;

use App\Factories\Models\UserFactory;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class UserFactoryTest extends AbstractIntegrationTestCase
{
    protected UserFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new UserFactory();
    }

    #[Test]
    public function it_makes_a_user(): void
    {
        $user = $this->factory->make('a', 'b', 'c');

        self::assertSame('a', $user->name);
        self::assertSame('b', $user->email);
        self::assertTrue(Hash::check('c', $user->password));
    }
}
