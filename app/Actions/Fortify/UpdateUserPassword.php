<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Concerns\Fortify\PasswordValidationRules;
use App\Concerns\ValidatesUserClass;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

readonly class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;
    use ValidatesUserClass;

    public function __construct(
        protected ValidatorFactory $validatorFactory,
        protected Translator $translator,
    ) {
    }

    /**
     * @param array<string, string> $input
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function update(BaseUser $user, array $input): void
    {
        $user = $this->validateUserClass($user);

        /** @var Validator $validator */
        $validator = $this->validatorFactory->make($input, [
            'current_password' => $this->currentPasswordRules(),
            'password'         => $this->passwordRules(),
        ], [
            'current_password.current_password' => $this->translator->get('The provided password does not match your current password.'),
        ]);

        /** @var array{password: string} $validated */
        $validated = $validator->validateWithBag('updatePassword');

        $user->password = $validated['password'];

        $user->save();
    }
}
