<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use InvalidArgumentException;

trait ValidatesUserClass
{
    /**
     * @throws InvalidArgumentException
     */
    protected function validateUserClass(?Authenticatable $user): User
    {
        if ($user instanceof User) {
            return $user;
        }

        throw new InvalidArgumentException('User must be an instance of ' . User::class);
    }
}
