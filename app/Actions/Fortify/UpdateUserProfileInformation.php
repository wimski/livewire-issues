<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Concerns\Fortify\EmailValidationRules;
use App\Concerns\Fortify\NameValidationRules;
use App\Concerns\ValidatesUserClass;
use App\Models\User;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

readonly class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use EmailValidationRules;
    use NameValidationRules;
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
    public function update(BaseUser $user, array $input): void
    {
        $user = $this->validateUserClass($user);

        /** @var Validator $validator */
        $validator = $this->validatorFactory->make($input, [
            'name'  => $this->nameRules(),
            'email' => array_merge($this->emailRules(), [
                ValidationRuleMaker::unique(User::class)->ignore($user),
            ]),
        ]);

        /** @var array{name: string, email: string} $validated */
        $validated = $validator->validateWithBag('updateProfileInformation');

        $oldEmail = $user->email;

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($user->email !== $oldEmail) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->email !== $oldEmail) {
            $user->sendEmailVerificationNotification();
        }
    }
}
