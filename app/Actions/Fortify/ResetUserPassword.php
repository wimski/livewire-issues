<?php

namespace App\Actions\Fortify;

use App\Concerns\Fortify\PasswordValidationRules;
use App\Concerns\ValidatesUserClass;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

readonly class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;
    use ValidatesUserClass;

    public function __construct(
        protected ValidatorFactory $validatorFactory,
    ) {
    }

    /**
     * @param array<string, string> $input
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function reset(BaseUser $user, array $input): void
    {
        $user = $this->validateUserClass($user);

        /** @var array{password: string} $validated */
        $validated = $this->validatorFactory->make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->password = $validated['password'];

        $user->save();
    }
}
