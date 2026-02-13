<?php

declare(strict_types=1);

namespace App\Factories\Models;

use App\Contracts\Factories\Models\UserFactoryInterface;
use App\Models\User;

readonly class UserFactory implements UserFactoryInterface
{
    public function make(string $name, string $email, string $password): User
    {
        $user = new User();

        $user->name     = $name;
        $user->email    = $email;
        $user->password = $password;

        return $user;
    }
}
