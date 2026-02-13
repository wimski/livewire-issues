<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Wimski\ModelRepositories\Contracts\Repositories\ModelRepositoryInterface;

/**
 * @extends ModelRepositoryInterface<User>
 */
interface UserRepositoryInterface extends ModelRepositoryInterface
{
}
