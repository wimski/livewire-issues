<?php

namespace App\Actions\Fortify;

use App\Concerns\Fortify\EmailValidationRules;
use App\Concerns\Fortify\NameValidationRules;
use App\Concerns\Fortify\PasswordValidationRules;
use App\Contracts\Factories\Models\UserFactoryInterface;
use App\Models\User;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Wimski\LaravelUtils\Validation\ValidationRuleMaker;

readonly class CreateNewUser implements CreatesNewUsers
{
    use EmailValidationRules;
    use NameValidationRules;
    use PasswordValidationRules;

    public function __construct(
        protected ValidatorFactory $validatorFactory,
        protected UserFactoryInterface $userFactory,
    ) {
    }

    /**
     * @param array<string, string> $input
     * @throws ValidationException
     */
    public function create(array $input): BaseUser
    {
        /** @var array{name: string, email: string, password: string} $validated */
        $validated = $this->validatorFactory->make($input, [
            'name'  => $this->nameRules(),
            'email' => array_merge($this->emailRules(), [
                ValidationRuleMaker::unique(User::class),
            ]),
            'password' => $this->passwordRules(),
        ])->validate();

        $user = $this->userFactory->make(
            $validated['name'],
            $validated['email'],
            $validated['password'],
        );

        $user->save();

        return $user;
    }
}
