<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Actions\Fortify;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class UpdateUserProfileInformationTest extends AbstractIntegrationTestCase
{
    protected UpdateUserProfileInformation $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new UpdateUserProfileInformation(
            $this->app->make(ValidatorFactory::class),
        );
    }

    #[Test]
    public function it_updates_a_users_profile_information(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'name'              => 'a',
            'email'             => 'b@b.b',
            'email_verified_at' => CarbonImmutable::now(),
        ]);

        $this->action->update($user, [
            'name'  => 'c',
            'email' => 'd@d.d',
        ]);

        self::assertSame('c', $user->name);
        self::assertSame('d@d.d', $user->email);
        self::assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
