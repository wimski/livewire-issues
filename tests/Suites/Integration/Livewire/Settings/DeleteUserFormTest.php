<?php

declare(strict_types=1);

namespace Tests\Suites\Integration\Livewire\Settings;

use App\Livewire\Settings\DeleteUserForm;
use App\Models\User;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\Suites\Integration\AbstractIntegrationTestCase;

class DeleteUserFormTest extends AbstractIntegrationTestCase
{
    #[Test]
    public function it_deletes_a_user(): void
    {
        $user = User::factory()->create(['password' => 'xxx']);

        $this->actingAs($user);

        Livewire::test(DeleteUserForm::class)
            ->set('password', 'xxx')
            ->call('deleteUser')
            ->assertRedirect('http://test.app:8000');

        self::assertFalse($user->exists);
    }
}
