<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Models;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class UserTest extends AbstractIntegrationTestCase
{
    #[Test]
    public function it_hashes_a_password(): void
    {
        $user = new User();

        $user->password = 'secret';

        $info = Hash::info($user->password);

        self::assertNotNull($info['algo']);
    }

    #[Test]
    public function it_does_not_rehash_a_password(): void
    {
        $user = new User();

        $hash = Hash::make('secret');

        $user->password = $hash;

        self::assertSame($hash, $user->password);
    }
}
