<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (! App::isLocal()) {
            return;
        }

        /** @var list<array{name: string, email: string, password: string}> $users */
        $users = config_array('auth.development_users');

        foreach ($users as $user) {
            if (User::query()->where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => $user['password'],
            ]);
        }
    }
}
