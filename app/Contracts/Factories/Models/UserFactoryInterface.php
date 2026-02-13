<?php

namespace App\Contracts\Factories\Models;

use App\Models\User;

interface UserFactoryInterface
{
    public function make(string $name, string $email, string $password): User;
}
