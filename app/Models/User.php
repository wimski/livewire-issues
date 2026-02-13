<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Wimski\LaravelUtils\Enums\ModelCastEnum;

/**
 * @property string               $id
 * @property string               $name
 * @property string               $email
 * @property CarbonImmutable|null $email_verified_at
 * @property string               $password
 * @property string|null          $two_factor_secret
 * @property string|null          $two_factor_recovery_codes
 * @property CarbonImmutable|null $two_factor_confirmed_at
 * @property string|null          $remember_token
 * @property CarbonImmutable      $created_at
 * @property CarbonImmutable      $updated_at
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends BaseUser implements MustVerifyEmail
{
    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $keyType    = 'string';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => ModelCastEnum::DATETIME(),
            'password'                => ModelCastEnum::HASHED->value,
            'two_factor_confirmed_at' => ModelCastEnum::DATETIME(),
        ];
    }

    public function getKey(): string
    {
        /** @var string $key */
        $key = parent::getKey();

        return $key;
    }

    /**
     * @return Attribute<string, string>
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $password): string => Hash::isHashed($password) ? $password : Hash::make($password),
        );
    }
}
